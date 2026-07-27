import { LabelHTMLAttributes, PropsWithChildren } from "react";

type InputLabelProps = PropsWithChildren<
    LabelHTMLAttributes<HTMLLabelElement> & {
        value?: string;
    }
>;

export default function InputLabel({
    value,
    className = "",
    children,
    ...props
}: InputLabelProps) {
    return (
        <label {...props} className={`nb-label ${className}`}>
            {value ?? children}
        </label>
    );
}
