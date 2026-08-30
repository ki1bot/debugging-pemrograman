import CodeMirror from "@uiw/react-codemirror";
import { useEffect, useState } from "react";
import type { ComponentProps } from "react";

export type EditorLanguage =
    | "javascript"
    | "php"
    | "sql"
    | "c"
    | "cpp"
    | "go"
    | "golang"
    | "java"
    | "python"
    | (string & {});

type CodeEditorProps = {
    value: string;
    onChange?: (value: string) => void;
    language: EditorLanguage;
    readOnly?: boolean;
    minHeight?: string;
};

type CodeMirrorExtensions = NonNullable<
    ComponentProps<typeof CodeMirror>["extensions"]
>;

const editorBasicSetup = {
    lineNumbers: true,
    highlightActiveLineGutter: true,
    highlightActiveLine: true,
    foldGutter: true,
    autocompletion: true,
    bracketMatching: true,
    closeBrackets: true,
    indentOnInput: true,
};

async function languageExtension(language: EditorLanguage) {
    switch (language.toLowerCase()) {
        case "php": {
            const { php } = await import("@codemirror/lang-php");
            return php();
        }

        case "sql": {
            const { sql } = await import("@codemirror/lang-sql");
            return sql();
        }

        case "c":
        case "cpp":
        case "c++": {
            const { cpp } = await import("@codemirror/lang-cpp");
            return cpp();
        }

        case "go":
        case "golang": {
            const { go } = await import("@codemirror/lang-go");
            return go();
        }

        case "java": {
            const { java } = await import("@codemirror/lang-java");
            return java();
        }

        case "python":
        case "py": {
            const { python } = await import("@codemirror/lang-python");
            return python();
        }

        case "javascript":
        case "typescript":
        case "js":
        case "ts":
        default: {
            const { javascript } = await import("@codemirror/lang-javascript");
            return javascript({
                jsx: true,
                typescript: true,
            });
        }
    }
}

export default function CodeEditor({
    value,
    onChange,
    language,
    readOnly = false,
    minHeight = "360px",
}: CodeEditorProps) {
    const [extensions, setExtensions] = useState<CodeMirrorExtensions>([]);

    useEffect(() => {
        let active = true;

        setExtensions([]);

        void languageExtension(language).then((extension) => {
            if (active) {
                setExtensions([extension]);
            }
        });

        return () => {
            active = false;
        };
    }, [language]);

    return (
        <div className="code-editor-shell">
            <CodeMirror
                value={value}
                height={minHeight}
                theme="dark"
                extensions={extensions}
                editable={!readOnly}
                basicSetup={editorBasicSetup}
                onChange={(nextValue: string) => onChange?.(nextValue)}
            />
        </div>
    );
}
