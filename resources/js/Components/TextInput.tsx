import { Eye, EyeOff } from "lucide-react";
import {
    forwardRef,
    InputHTMLAttributes,
    useEffect,
    useImperativeHandle,
    useRef,
    useState,
} from "react";

type TextInputProps = InputHTMLAttributes<HTMLInputElement> & {
    isFocused?: boolean;
    showPasswordToggle?: boolean;
};

const TextInput = forwardRef<HTMLInputElement, TextInputProps>(
    function TextInput(
        {
            type = "text",
            className = "",
            isFocused = false,
            showPasswordToggle = true,
            ...props
        },
        ref,
    ) {
        const localRef = useRef<HTMLInputElement>(null);
        const [passwordVisible, setPasswordVisible] = useState(false);

        const canTogglePassword = showPasswordToggle && type === "password";

        const currentType =
            canTogglePassword && passwordVisible ? "text" : type;

        const toggleLabel = passwordVisible
            ? "Sembunyikan kata sandi"
            : "Tampilkan kata sandi";

        useImperativeHandle(ref, () => localRef.current as HTMLInputElement);

        useEffect(() => {
            if (isFocused) {
                localRef.current?.focus();
            }
        }, [isFocused]);

        const inputElement = (
            <input
                {...props}
                ref={localRef}
                type={currentType}
                className={`nb-input ${
                    canTogglePassword ? "pr-16" : ""
                } ${className}`}
            />
        );

        if (!canTogglePassword) {
            return inputElement;
        }

        return (
            <div className="relative">
                {inputElement}

                <button
                    type="button"
                    onClick={() =>
                        setPasswordVisible(
                            (currentVisibility) => !currentVisibility,
                        )
                    }
                    disabled={props.disabled}
                    aria-label={toggleLabel}
                    aria-controls={props.id}
                    aria-pressed={passwordVisible}
                    title={toggleLabel}
                    className="absolute right-3 top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-lg border-2 border-[#21162f] bg-white text-[#21162f] transition hover:bg-[#ffd93d] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#9c88f7]/50 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {passwordVisible ? (
                        <EyeOff
                            className="h-5 w-5"
                            strokeWidth={2.5}
                            aria-hidden="true"
                        />
                    ) : (
                        <Eye
                            className="h-5 w-5"
                            strokeWidth={2.5}
                            aria-hidden="true"
                        />
                    )}
                </button>
            </div>
        );
    },
);

export default TextInput;
