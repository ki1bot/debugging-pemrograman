import {
    PropsWithChildren,
    useEffect,
} from 'react';

type ModalProps = PropsWithChildren<{
    show?: boolean;
    maxWidth?:
        | 'sm'
        | 'md'
        | 'lg'
        | 'xl'
        | '2xl';
    closeable?: boolean;
    onClose: () => void;
}>;

const maxWidthClasses = {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
};

export default function Modal({
    show = false,
    maxWidth = '2xl',
    closeable = true,
    onClose,
    children,
}: ModalProps) {
    useEffect(() => {
        const handleEscape = (event: KeyboardEvent) => {
            if (event.key === 'Escape' && closeable) {
                onClose();
            }
        };

        document.addEventListener('keydown', handleEscape);

        if (show) {
            document.body.style.overflow = 'hidden';
        }

        return () => {
            document.removeEventListener(
                'keydown',
                handleEscape,
            );

            document.body.style.overflow = '';
        };
    }, [show, closeable, onClose]);

    if (!show) {
        return null;
    }

    return (
        <div className="fixed inset-0 z-[100] overflow-y-auto px-4 py-8">
            <button
                type="button"
                className="fixed inset-0 h-full w-full bg-black/70"
                onClick={() => {
                    if (closeable) {
                        onClose();
                    }
                }}
                aria-label="Tutup modal"
            />

            <div
                className={`relative mx-auto w-full ${maxWidthClasses[maxWidth]}`}
            >
                <div className="nb-card bg-white p-6 sm:p-8">
                    {children}
                </div>
            </div>
        </div>
    );
}
