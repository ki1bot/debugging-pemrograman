import { InputHTMLAttributes } from "react";

export default function Checkbox({
    className = "",
    ...props
}: InputHTMLAttributes<HTMLInputElement>) {
    return (
        <input
            {...props}
            type="checkbox"
            className={`h-5 w-5 appearance-none border-[3px] border-black bg-white checked:bg-[#ffd93d] checked:shadow-[inset_0_0_0_3px_white] focus:outline-none ${className}`}
        />
    );
}
