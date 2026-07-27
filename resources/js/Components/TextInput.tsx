import {
    forwardRef,
    InputHTMLAttributes,
    useEffect,
    useImperativeHandle,
    useRef,
} from "react";

type TextInputProps = InputHTMLAttributes<HTMLInputElement> & {
    isFocused?: boolean;
};

const TextInput = forwardRef<HTMLInputElement, TextInputProps>(
    function TextInput(
        { type = "text", className = "", isFocused = false, ...props },
        ref,
    ) {
        const localRef = useRef<HTMLInputElement>(null);

        useImperativeHandle(ref, () => localRef.current as HTMLInputElement);

        useEffect(() => {
            if (isFocused) {
                localRef.current?.focus();
            }
        }, [isFocused]);

        return (
            <input
                {...props}
                ref={localRef}
                type={type}
                className={`nb-input ${className}`}
            />
        );
    },
);

export default TextInput;
