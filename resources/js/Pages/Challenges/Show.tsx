import CodeEditor from "@/Components/CodeEditor";
import StatusBadge from "@/Components/StatusBadge";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { ChallengeDetail } from "@/types";
import { Head, router, useForm } from "@inertiajs/react";
import { FormEvent, useMemo, useState } from "react";

type ChallengeShowProps = {
    challenge: ChallengeDetail;
    progress: {
        best_score: number;
        attempts_count: number;
        hints_used: number;
        hint_penalty: number;
        is_completed: boolean;
    };
};

type SubmissionForm = {
    selected_line: number;
    submitted_code: string;
    submitted_explanation: string;
};

type ExecutionSubmission = {
    token: string;
    status: {
        id: number;
        description: string;
    };
};

type ExecutionResult = {
    token: string;
    finished: boolean;
    status: {
        id: number;
        description: string;
    };
    stdout: string | null;
    stderr: string | null;
    compile_output: string | null;
    message: string | null;
    time: string | null;
    memory: number | null;
};

type ErrorPayload = {
    message?: string;
    errors?: Record<string, string[]>;
};

const delay = (milliseconds: number) =>
    new Promise<void>((resolve) => window.setTimeout(resolve, milliseconds));

function csrfToken(): string {
    return (
        document
            .querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
            ?.getAttribute("content") ?? ""
    );
}

function errorMessage(payload: ErrorPayload): string {
    if (payload.message) {
        return payload.message;
    }

    if (payload.errors) {
        const firstError = Object.values(payload.errors).flat()[0];

        if (firstError) {
            return firstError;
        }
    }

    return "Permintaan tidak dapat diproses.";
}

async function requestJson<T>(
    url: string,
    options: RequestInit = {},
): Promise<T> {
    const headers = new Headers(options.headers);
    headers.set("Accept", "application/json");
    headers.set("Content-Type", "application/json");
    headers.set("X-CSRF-TOKEN", csrfToken());

    const response = await fetch(url, {
        ...options,
        cache: "no-store",
        credentials: "same-origin",
        headers,
    });

    const payload = (await response.json().catch(() => ({}))) as ErrorPayload;

    if (!response.ok) {
        throw new Error(errorMessage(payload));
    }

    return payload as T;
}

function ExecutionOutput({
    title,
    value,
}: {
    title: string;
    value: string | null;
}) {
    if (!value) {
        return null;
    }

    return (
        <div className="border-[3px] border-black bg-[#111111] p-4 text-white shadow-[4px_4px_0_#111]">
            <p className="text-xs font-black uppercase tracking-[0.14em] text-[#ffd93d]">
                {title}
            </p>
            <pre className="mt-3 max-h-72 overflow-auto whitespace-pre-wrap break-words font-mono text-sm leading-6">
                {value}
            </pre>
        </div>
    );
}

export default function ChallengeShow({
    challenge,
    progress,
}: ChallengeShowProps) {
    const lines = useMemo(
        () => challenge.broken_code.split(/\r\n|\r|\n/),
        [challenge.broken_code],
    );

    const { data, setData, post, processing, errors } = useForm<SubmissionForm>(
        {
            selected_line: 0,
            submitted_code: challenge.broken_code,
            submitted_explanation: "",
        },
    );

    const [unlockingHint, setUnlockingHint] = useState<number | null>(null);
    const [stdin, setStdin] = useState("");
    const [runningCode, setRunningCode] = useState(false);
    const [executionResult, setExecutionResult] =
        useState<ExecutionResult | null>(null);
    const [executionError, setExecutionError] = useState<string | null>(null);

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        post(route("challenges.submit", challenge.slug), {
            preserveScroll: true,
        });
    };

    const unlockHint = (hintId: number) => {
        setUnlockingHint(hintId);

        router.post(
            route("challenges.hints.store", {
                challenge: challenge.slug,
                hint: hintId,
            }),
            {},
            {
                preserveScroll: true,
                onFinish: () => setUnlockingHint(null),
            },
        );
    };

    const executeCode = async () => {
        setRunningCode(true);
        setExecutionResult(null);
        setExecutionError(null);

        try {
            const submission = await requestJson<ExecutionSubmission>(
                route("code-executions.store"),
                {
                    method: "POST",
                    body: JSON.stringify({
                        language: challenge.category.slug,
                        source_code: data.submitted_code,
                        stdin,
                    }),
                },
            );

            for (let attempt = 0; attempt < 20; attempt += 1) {
                if (attempt > 0) {
                    await delay(1000);
                }

                const result = await requestJson<ExecutionResult>(
                    route("code-executions.show", {
                        token: submission.token,
                    }),
                );

                setExecutionResult(result);

                if (result.finished) {
                    return;
                }
            }

            throw new Error(
                "Eksekusi belum selesai setelah 20 detik. Jalankan kembali beberapa saat lagi.",
            );
        } catch (error) {
            setExecutionError(
                error instanceof Error
                    ? error.message
                    : "Eksekusi kode gagal diproses.",
            );
        } finally {
            setRunningCode(false);
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex flex-col justify-between gap-5 md:flex-row md:items-end">
                    <div>
                        <div className="flex flex-wrap gap-3">
                            <span className="nb-badge bg-[#9ed8ff]">
                                {challenge.category.name}
                            </span>

                            <span className="nb-badge bg-[#ffbd70]">
                                {challenge.difficulty.name}
                            </span>

                            {progress.is_completed && (
                                <StatusBadge status="completed" />
                            )}
                        </div>

                        <h1 className="mt-5 text-3xl font-black tracking-[-0.05em] sm:text-4xl">
                            {challenge.title}
                        </h1>
                    </div>

                    <div className="border-[3px] border-black bg-white px-5 py-3 font-black shadow-[4px_4px_0_#111]">
                        Maksimal {challenge.base_points} poin
                    </div>
                </div>
            }
        >
            <Head title={challenge.title} />

            <div className="mx-auto max-w-[1500px] px-4 py-10 sm:px-6 lg:px-8">
                <section className="grid gap-6 lg:grid-cols-4">
                    <article className="nb-card bg-[#ffd93d] p-5">
                        <p className="text-xs font-black uppercase tracking-[0.14em]">
                            Skor Terbaik
                        </p>

                        <p className="mt-3 text-4xl font-black">
                            {progress.best_score}
                        </p>
                    </article>

                    <article className="nb-card bg-[#9ed8ff] p-5">
                        <p className="text-xs font-black uppercase tracking-[0.14em]">
                            Percobaan
                        </p>

                        <p className="mt-3 text-4xl font-black">
                            {progress.attempts_count}
                        </p>
                    </article>

                    <article className="nb-card bg-[#b7a4ff] p-5">
                        <p className="text-xs font-black uppercase tracking-[0.14em]">
                            Hint Dibuka
                        </p>

                        <p className="mt-3 text-4xl font-black">
                            {progress.hints_used}
                        </p>
                    </article>

                    <article className="nb-card bg-[#ff9c9c] p-5">
                        <p className="text-xs font-black uppercase tracking-[0.14em]">
                            Penalti Hint
                        </p>

                        <p className="mt-3 text-4xl font-black">
                            {progress.hint_penalty}%
                        </p>
                    </article>
                </section>

                <section className="mt-8 grid gap-7 xl:grid-cols-[380px_minmax(0,1fr)]">
                    <div className="space-y-7">
                        <article className="nb-card bg-white p-6">
                            <span className="nb-badge bg-[#ffd93d]">
                                Deskripsi Masalah
                            </span>

                            <p className="mt-5 font-semibold leading-8 text-neutral-700">
                                {challenge.description}
                            </p>
                        </article>

                        <article className="nb-card bg-[#fff1a8] p-6">
                            <div className="flex items-center justify-between gap-4">
                                <span className="nb-badge bg-white">
                                    Petunjuk
                                </span>

                                <span className="text-sm font-black">
                                    Penalti diterapkan saat dibuka
                                </span>
                            </div>

                            <div className="mt-5 grid gap-4">
                                {challenge.hints.map((hint) => (
                                    <div
                                        key={hint.id}
                                        className="border-[3px] border-black bg-white p-4 shadow-[3px_3px_0_#111]"
                                    >
                                        <div className="flex items-center justify-between gap-3">
                                            <strong>
                                                Hint {hint.hint_order}
                                            </strong>

                                            <span className="nb-badge bg-[#ff9c9c]">
                                                -{hint.point_penalty}%
                                            </span>
                                        </div>

                                        {hint.unlocked ? (
                                            <p className="mt-4 font-semibold leading-7 text-neutral-700">
                                                {hint.content}
                                            </p>
                                        ) : (
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    unlockHint(hint.id)
                                                }
                                                disabled={
                                                    unlockingHint !== null
                                                }
                                                className="nb-button mt-4 w-full bg-[#9ed8ff] text-sm"
                                            >
                                                {unlockingHint === hint.id
                                                    ? "Membuka..."
                                                    : "Buka Hint"}
                                            </button>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </article>

                        <article className="nb-card bg-[#111111] p-6 text-white">
                            <p className="text-sm font-black uppercase tracking-[0.14em] text-[#ffd93d]">
                                Sistem Penilaian
                            </p>

                            <div className="mt-5 grid gap-3 font-bold">
                                <div className="flex justify-between border-b-2 border-white/30 pb-3">
                                    <span>Baris salah</span>
                                    <span>30%</span>
                                </div>

                                <div className="flex justify-between border-b-2 border-white/30 pb-3">
                                    <span>Kode perbaikan</span>
                                    <span>50%</span>
                                </div>

                                <div className="flex justify-between">
                                    <span>Penjelasan</span>
                                    <span>20%</span>
                                </div>
                            </div>
                        </article>
                    </div>

                    <form onSubmit={submit} className="space-y-8">
                        <section className="nb-card bg-white p-5 sm:p-6">
                            <div className="mb-5">
                                <span className="nb-badge bg-[#ff9c9c]">
                                    Tahap 1
                                </span>

                                <h2 className="mt-4 text-2xl font-black">
                                    Pilih baris yang bermasalah
                                </h2>

                                <p className="mt-2 font-semibold text-neutral-700">
                                    Klik satu baris kode yang menurut Anda
                                    menjadi sumber bug.
                                </p>
                            </div>

                            <div className="overflow-hidden border-[3px] border-black bg-white shadow-[5px_5px_0_#111]">
                                {lines.map((line, index) => {
                                    const lineNumber = index + 1;
                                    const selected =
                                        data.selected_line === lineNumber;

                                    return (
                                        <button
                                            key={`${lineNumber}-${line}`}
                                            type="button"
                                            onClick={() =>
                                                setData(
                                                    "selected_line",
                                                    lineNumber,
                                                )
                                            }
                                            className={`code-line ${
                                                selected
                                                    ? "code-line-selected"
                                                    : "hover:bg-[#fff1a8]"
                                            }`}
                                        >
                                            <span className="code-line-number">
                                                {lineNumber}
                                            </span>

                                            <span className="code-line-content">
                                                {line || " "}
                                            </span>
                                        </button>
                                    );
                                })}
                            </div>

                            {data.selected_line > 0 && (
                                <p className="mt-4 border-2 border-black bg-[#9ef0b8] p-3 font-black">
                                    Baris dipilih: {data.selected_line}
                                </p>
                            )}

                            {errors.selected_line && (
                                <p className="mt-4 border-2 border-black bg-[#ff9c9c] p-3 font-bold">
                                    {errors.selected_line}
                                </p>
                            )}
                        </section>

                        <section className="nb-card bg-[#9ed8ff] p-5 sm:p-6">
                            <div className="mb-5">
                                <span className="nb-badge bg-white">
                                    Tahap 2
                                </span>

                                <h2 className="mt-4 text-2xl font-black">
                                    Perbaiki dan jalankan kode
                                </h2>

                                <p className="mt-2 font-semibold text-neutral-700">
                                    Ubah kode menjadi versi yang benar, lalu
                                    jalankan di sandbox. Hasil eksekusi membantu
                                    pengujian, tetapi penilaian akhir tetap
                                    memakai solusi tantangan.
                                </p>
                            </div>

                            <CodeEditor
                                value={data.submitted_code}
                                onChange={(value) =>
                                    setData("submitted_code", value)
                                }
                                language={challenge.category.slug}
                                minHeight="420px"
                            />

                            <div className="mt-5 grid gap-4">
                                <div>
                                    <label
                                        htmlFor="execution-stdin"
                                        className="text-sm font-black uppercase tracking-[0.12em]"
                                    >
                                        Standard Input
                                    </label>

                                    <textarea
                                        id="execution-stdin"
                                        value={stdin}
                                        onChange={(event) =>
                                            setStdin(event.target.value)
                                        }
                                        className="nb-input mt-2 min-h-28 resize-y bg-white font-mono"
                                        placeholder="Masukkan input program apabila diperlukan."
                                        maxLength={5000}
                                    />

                                    <div className="mt-2 text-right text-sm font-black">
                                        {stdin.length}/5000
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    onClick={executeCode}
                                    disabled={
                                        runningCode ||
                                        data.submitted_code.trim() === ""
                                    }
                                    className="nb-button w-full bg-[#9ef0b8] px-6 py-4 text-base"
                                >
                                    {runningCode
                                        ? "Menjalankan Kode..."
                                        : "Jalankan Kode"}
                                </button>

                                {executionError && (
                                    <p className="border-[3px] border-black bg-[#ff9c9c] p-4 font-bold shadow-[4px_4px_0_#111]">
                                        {executionError}
                                    </p>
                                )}

                                {executionResult && (
                                    <div className="grid gap-4 border-[3px] border-black bg-white p-4 shadow-[5px_5px_0_#111]">
                                        <div className="flex flex-wrap items-center justify-between gap-3">
                                            <span className="nb-badge bg-[#ffd93d]">
                                                {
                                                    executionResult.status
                                                        .description
                                                }
                                            </span>

                                            <div className="flex flex-wrap gap-3 text-sm font-black">
                                                <span>
                                                    Waktu:{" "}
                                                    {executionResult.time ??
                                                        "-"}{" "}
                                                    detik
                                                </span>

                                                <span>
                                                    Memori:{" "}
                                                    {executionResult.memory ??
                                                        "-"}{" "}
                                                    KB
                                                </span>
                                            </div>
                                        </div>

                                        <ExecutionOutput
                                            title="Output Program"
                                            value={executionResult.stdout}
                                        />

                                        <ExecutionOutput
                                            title="Kesalahan Kompilasi"
                                            value={
                                                executionResult.compile_output
                                            }
                                        />

                                        <ExecutionOutput
                                            title="Kesalahan Runtime"
                                            value={executionResult.stderr}
                                        />

                                        <ExecutionOutput
                                            title="Pesan Sistem"
                                            value={executionResult.message}
                                        />

                                        {executionResult.finished &&
                                            !executionResult.stdout &&
                                            !executionResult.compile_output &&
                                            !executionResult.stderr &&
                                            !executionResult.message && (
                                                <p className="border-[3px] border-black bg-[#9ef0b8] p-4 font-black">
                                                    Program selesai tanpa
                                                    output.
                                                </p>
                                            )}
                                    </div>
                                )}
                            </div>

                            {errors.submitted_code && (
                                <p className="mt-4 border-2 border-black bg-[#ff9c9c] p-3 font-bold">
                                    {errors.submitted_code}
                                </p>
                            )}
                        </section>

                        <section className="nb-card bg-[#b7a4ff] p-5 sm:p-6">
                            <div className="mb-5">
                                <span className="nb-badge bg-white">
                                    Tahap 3
                                </span>

                                <h2 className="mt-4 text-2xl font-black">
                                    Jelaskan penyebabnya
                                </h2>

                                <p className="mt-2 font-semibold text-neutral-700">
                                    Gunakan penjelasan teknis minimal 20
                                    karakter. Penilaian teks menggunakan kata
                                    kunci dasar.
                                </p>
                            </div>

                            <textarea
                                value={data.submitted_explanation}
                                onChange={(event) =>
                                    setData(
                                        "submitted_explanation",
                                        event.target.value,
                                    )
                                }
                                className="nb-input min-h-48 resize-y"
                                placeholder="Jelaskan apa yang salah, mengapa error terjadi, dan bagaimana perbaikannya bekerja."
                                maxLength={3000}
                            />

                            <div className="mt-3 flex justify-between gap-4 text-sm font-black">
                                <span>Minimal 20 karakter</span>

                                <span>
                                    {data.submitted_explanation.length}
                                    /3000
                                </span>
                            </div>

                            {errors.submitted_explanation && (
                                <p className="mt-4 border-2 border-black bg-[#ff9c9c] p-3 font-bold">
                                    {errors.submitted_explanation}
                                </p>
                            )}
                        </section>

                        <button
                            type="submit"
                            disabled={processing}
                            className="nb-button w-full bg-[#ffd93d] px-6 py-5 text-lg"
                        >
                            {processing
                                ? "Memeriksa Jawaban..."
                                : "Kirim dan Periksa Jawaban"}
                        </button>
                    </form>
                </section>
            </div>
        </AuthenticatedLayout>
    );
}
