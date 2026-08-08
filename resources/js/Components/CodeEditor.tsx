import { cpp } from "@codemirror/lang-cpp";
import { go } from "@codemirror/lang-go";
import { java } from "@codemirror/lang-java";
import { javascript } from "@codemirror/lang-javascript";
import { php } from "@codemirror/lang-php";
import { python } from "@codemirror/lang-python";
import { sql } from "@codemirror/lang-sql";
import CodeMirror from "@uiw/react-codemirror";
import { useMemo } from "react";

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

function languageExtension(language: EditorLanguage) {
    switch (language.toLowerCase()) {
        case "php":
            return php();

        case "sql":
            return sql();

        case "c":
        case "cpp":
        case "c++":
            return cpp();

        case "go":
        case "golang":
            return go();

        case "java":
            return java();

        case "python":
        case "py":
            return python();

        case "javascript":
        case "typescript":
        case "js":
        case "ts":
            return javascript({
                jsx: true,
                typescript: true,
            });

        default:
            return javascript({
                jsx: true,
                typescript: true,
            });
    }
}

export default function CodeEditor({
    value,
    onChange,
    language,
    readOnly = false,
    minHeight = "360px",
}: CodeEditorProps) {
    const extensions = useMemo(
        () => [languageExtension(language)],
        [language],
    );

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