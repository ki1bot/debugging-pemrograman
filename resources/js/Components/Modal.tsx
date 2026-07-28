import { PropsWithChildren, useEffect, useRef } from "react";

type ModalProps = PropsWithChildren<{
    show?: boolean;
    maxWidth?: "sm" | "md" | "lg" | "xl" | "2xl";
    closeable?: boolean;
    onClose: () => void;
}>;

const maxWidthClasses = {
    sm: "sm:max-w-sm",
    md: "sm:max-w-md",
    lg: "sm:max-w-lg",
    xl: "sm:max-w-xl",
    "2xl": "sm:max-w-2xl",
};

export default function Modal({
    show = false,
    maxWidth = "2xl",
    closeable = true,
    onClose,
    children,
}: ModalProps) {
    const panelRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!show) {
            return;
        }

        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = "hidden";
        panelRef.current?.focus();

        const handleEscape = (event: KeyboardEvent) => {
            if (event.key === "Escape" && closeable) {
                onClose();
            }
        };

        document.addEventListener("keydown", handleEscape);

        return () => {
            document.removeEventListener("keydown", handleEscape);
            document.body.style.overflow = previousOverflow;
        };
    }, [show, closeable, onClose]);

    if (!show) {
        return null;
    }

    return (
        <div className="fixed inset-0 z-[100] overflow-y-auto p-2 sm:p-6">
            <button
                type="button"
                className="nb-modal-backdrop"
                onClick={() => {
                    if (closeable) {
                        onClose();
                    }
                }}
                aria-label="Tutup modal"
            />

            <div className="flex min-h-full items-center justify-center">
                <div
                    ref={panelRef}
                    role="dialog"
                    aria-modal="true"
                    tabIndex={-1}
                    className={`nb-modal-panel w-full ${maxWidthClasses[maxWidth]}`}
                >
                    <div className="p-5 sm:p-8">{children}</div>
                </div>
            </div>
        </div>
    );
}
