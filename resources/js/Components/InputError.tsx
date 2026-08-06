import { HTMLAttributes } from "react";

type InputErrorProps = HTMLAttributes<HTMLParagraphElement> & {
    message?: string;
};

export default function InputError({
    message,
    className = "",
    ...props
}: InputErrorProps) {
    if (!message) {
        return null;
    }

    return (
        <p
            {...props}
            className={`border-2 border-black bg-[#ff9c9c] px-3 py-2 text-sm font-bold shadow-[2px_2px_0_#111] ${className}`}
        >
            Email atau password yang anda masukkan salah
        </p>
    );
}
