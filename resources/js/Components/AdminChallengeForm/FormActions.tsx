import { Link } from "@inertiajs/react";

type FormActionsProps = {
    processing: boolean;
    editing: boolean;
};

export default function FormActions({ processing, editing }: FormActionsProps) {
    return (
        <div className="flex flex-wrap justify-end gap-4">
            <Link
                href={route("admin.challenges.index")}
                className="nb-button bg-white"
            >
                Batal
            </Link>

            <button
                disabled={processing}
                className="nb-button bg-[#9ef0b8] px-8"
            >
                {processing
                    ? "Menyimpan..."
                    : editing
                      ? "Simpan Perubahan"
                      : "Buat Tantangan"}
            </button>
        </div>
    );
}
