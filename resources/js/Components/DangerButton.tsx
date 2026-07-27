import { ButtonHTMLAttributes } from "react";

export default function DangerButton({
    className = "",
    disabled,
    children,
    type = "submit",
    ...props
}: ButtonHTMLAttributes<HTMLButtonElement>) {
    return (
        <button
            {...props}
            type={type}
            disabled={disabled}
            className={`nb-button bg-[#ff6b6b] ${className}`}
        >
            {children}
        </button>
    );
}
