import { existsSync } from "node:fs";
import { copyFile, readdir, readFile, rm, writeFile } from "node:fs/promises";
import { join, resolve } from "node:path";

const root = resolve(".vercel/output/functions");

const bridge = `process.env.LAMBDA_TASK_ROOT = process.env.LAMBDA_TASK_ROOT || '/var/task';
const original = require('./launcher-orig.js');

exports.launcher = async (event, context) => {
    const request = { ...event };

    if (Array.isArray(request.body)) {
        request.body = Buffer.from(request.body);
    } else if (typeof request.body === 'string') {
        request.body = Buffer.from(
            request.body,
            request.isBase64Encoded ? 'base64' : 'utf8'
        );
    } else if (request.body == null) {
        delete request.body;
    }

    const headers = request.headers || {};
    request.host = request.host || headers['x-forwarded-host'] || headers.host || headers.Host;

    return original.launcher(request, context);
};
`;

async function findFunctions(directory) {
    const entries = await readdir(directory, {
        withFileTypes: true,
    });

    const found = [];

    for (const entry of entries) {
        if (!entry.isDirectory()) {
            continue;
        }

        const path = join(directory, entry.name);

        if (entry.name.endsWith(".func")) {
            found.push(path);
        } else {
            found.push(...(await findFunctions(path)));
        }
    }

    return found;
}

if (!existsSync(root)) {
    throw new Error(
        "Build output tidak ditemukan. Jalankan vercel build --prod terlebih dahulu.",
    );
}

let patched = 0;

for (const directory of await findFunctions(root)) {
    const configPath = join(directory, ".vc-config.json");

    if (!existsSync(configPath)) {
        continue;
    }

    const config = JSON.parse(await readFile(configPath, "utf8"));

    if (config.handler !== "launcher.launcher") {
        continue;
    }

    const launcher = join(directory, "launcher.js");
    const original = join(directory, "launcher-orig.js");

    const fileMap =
        config.filePathMap && typeof config.filePathMap === "object"
            ? config.filePathMap
            : {};

    const sourcePath =
        typeof fileMap["launcher.js"] === "string"
            ? fileMap["launcher.js"]
            : launcher;

    if (!existsSync(sourcePath)) {
        throw new Error(`Launcher asli tidak ditemukan: ${sourcePath}`);
    }

    await copyFile(sourcePath, original);
    await rm(launcher, { force: true });
    await writeFile(launcher, bridge);

    fileMap["launcher-orig.js"] = original;
    fileMap["launcher.js"] = launcher;

    config.filePathMap = fileMap;
    config.handler = "launcher.js";
    config.launcherType = "Nodejs";
    config.awsLambdaHandler = "launcher.launcher";

    await writeFile(configPath, JSON.stringify(config, null, 2) + "\n");

    patched++;

    process.stdout.write(`Patched ${configPath}\n`);
}

if (patched === 0) {
    throw new Error(
        "Tidak ditemukan function PHP dengan handler launcher.launcher. Periksa hasil build.",
    );
}
