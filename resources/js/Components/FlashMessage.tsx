import { PageProps } from "@/types";
import { usePage } from "@inertiajs/react";
import { useEffect, useState } from "react";

export default function FlashMessage() {
    const { flash } = usePage<PageProps>().props;
    const message = flash.success ?? flash.error ?? flash.info ?? null;
    const type = flash.success ? "success" : flash.error ? "error" : "info";
    const [visible, setVisible] = useState(Boolean(message));

    useEffect(() => {
        setVisible(Boolean(message));

        if (!message) {
            return;
        }

        const timeout = window.setTimeout(() => setVisible(false), 6500);

        return () => window.clearTimeout(timeout);
    }, [message]);

    if (!message || !visible) {
        return null;
    }

    const background =
        type === "success"
            ? "bg-[#9ce6b8]"
            : type === "error"
              ? "bg-[#ff9eb5]"
              : "bg-[#8ed8ff]";

    return (
        <div className="fixed inset-x-3 top-3 z-[100] sm:left-auto sm:right-4 sm:top-4 sm:w-[min(400px,calc(100vw-2rem))]">
            <div
                className={`nb-flash ${background}`}
                role={type === "error" ? "alert" : "status"}
                aria-live={type === "error" ? "assertive" : "polite"}
            >
                <p className="min-w-0 break-words">{message}</p>

                <button
                    type="button"
                    onClick={() => setVisible(false)}
                    className="grid h-9 w-9 shrink-0 place-items-center rounded-md border-2 border-black bg-white font-black shadow-[2px_2px_0_#171717]"
                    aria-label="Tutup pesan"
                >
                    ×
                </button>
            </div>
        </div>
    );
}
