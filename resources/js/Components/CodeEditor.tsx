import { javascript } from "@codemirror/lang-javascript";
import { php } from "@codemirror/lang-php";
import { sql } from "@codemirror/lang-sql";
import CodeMirror from "@uiw/react-codemirror";

export type EditorLanguage = "javascript" | "php" | "sql" | string;

type CodeEditorProps = {
    value: string;
    onChange?: (value: string) => void;
    language: EditorLanguage;
    readOnly?: boolean;
    minHeight?: string;
};

function languageExtension(language: EditorLanguage) {
    if (language === "php") {
        return php();
    }

    if (language === "sql") {
        return sql();
    }

    return javascript({
        jsx: true,
        typescript: true,
    });
}

export default function CodeEditor({
    value,
    onChange,
    language,
    readOnly = false,
    minHeight = "360px",
}: CodeEditorProps) {
    return (
        <div className="code-editor-shell">
            <CodeMirror
                value={value}
                height={minHeight}
                theme="dark"
                extensions={[languageExtension(language)]}
                editable={!readOnly}
                basicSetup={{
                    lineNumbers: true,
                    highlightActiveLineGutter: true,
                    highlightActiveLine: true,
                    foldGutter: true,
                    autocompletion: true,
                    bracketMatching: true,
                    closeBrackets: true,
                    indentOnInput: true,
                }}
                onChange={(nextValue: string) => onChange?.(nextValue)}
            />
        </div>
    );
}
