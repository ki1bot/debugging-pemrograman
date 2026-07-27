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
    }, [message]);

    if (!message || !visible) {
        return null;
    }

    const background =
        type === "success"
            ? "bg-[#9ef0b8]"
            : type === "error"
              ? "bg-[#ff9c9c]"
              : "bg-[#9ed8ff]";

    return (
        <div className="fixed right-4 top-4 z-[100] w-[min(390px,calc(100vw-2rem))]">
            <div
                className={`flex items-start justify-between gap-4 border-[3px] border-black p-4 font-bold shadow-[6px_6px_0_#111] ${background}`}
            >
                <p>{message}</p>

                <button
                    type="button"
                    onClick={() => setVisible(false)}
                    className="grid h-8 w-8 shrink-0 place-items-center border-2 border-black bg-white font-black shadow-[2px_2px_0_#111]"
                    aria-label="Tutup pesan"
                >
                    ×
                </button>
            </div>
        </div>
    );
}
