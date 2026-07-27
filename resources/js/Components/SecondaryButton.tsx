import { ButtonHTMLAttributes } from "react";

export default function SecondaryButton({
    className = "",
    disabled,
    children,
    type = "button",
    ...props
}: ButtonHTMLAttributes<HTMLButtonElement>) {
    return (
        <button
            {...props}
            type={type}
            disabled={disabled}
            className={`nb-button bg-white ${className}`}
        >
            {children}
        </button>
    );
}
