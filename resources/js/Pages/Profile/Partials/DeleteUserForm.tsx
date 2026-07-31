import DangerButton from "@/Components/DangerButton";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import TextInput from "@/Components/TextInput";
import { useForm } from "@inertiajs/react";
import { FormEventHandler, useRef, useState } from "react";

export default function DeleteUserForm({
    className = "",
}: {
    className?: string;
}) {
    const [confirmingUserDeletion, setConfirmingUserDeletion] = useState(false);

    const passwordInput = useRef<HTMLInputElement>(null);

    const {
        data,
        setData,
        delete: destroy,
        processing,
        reset,
        errors,
        clearErrors,
    } = useForm({
        password: "",
    });

    const confirmUserDeletion = () => {
        setConfirmingUserDeletion(true);
    };

    const closeModal = () => {
        setConfirmingUserDeletion(false);
        clearErrors();
        reset();
    };

    const deleteUser: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();

        destroy(route("profile.destroy"), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.current?.focus(),
            onFinish: () => reset(),
        });
    };

    return (
        <section className={className}>
            <span className="nb-badge bg-white">Zona Berbahaya</span>

            <h2 className="mt-5 text-2xl font-black tracking-[-0.04em]">
                Hapus Akun
            </h2>

            <p className="mt-3 max-w-2xl font-semibold leading-7 text-neutral-800">
                Penghapusan akun akan menghapus data pengguna, submission, poin,
                dan riwayat tantangan secara permanen.
            </p>

            <DangerButton
                type="button"
                className="mt-6"
                onClick={confirmUserDeletion}
            >
                Hapus Akun Saya
            </DangerButton>

            <Modal show={confirmingUserDeletion} onClose={closeModal}>
                <form onSubmit={deleteUser}>
                    <span className="nb-badge bg-[#ff9c9c]">Konfirmasi</span>

                    <h2 className="mt-5 text-3xl font-black tracking-[-0.05em]">
                        Hapus akun secara permanen?
                    </h2>

                    <p className="mt-4 font-semibold leading-7 text-neutral-700">
                        Masukkan password untuk memastikan bahwa Anda
                        benar-benar ingin menghapus akun.
                    </p>

                    <div className="mt-6">
                        <InputLabel htmlFor="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            ref={passwordInput}
                            value={data.password}
                            isFocused
                            onChange={(event) =>
                                setData("password", event.target.value)
                            }
                            placeholder="Password akun"
                        />

                        <InputError
                            message={errors.password}
                            className="mt-3"
                        />
                    </div>

                    <div className="mt-7 flex flex-wrap justify-end gap-4">
                        <SecondaryButton onClick={closeModal}>
                            Batal
                        </SecondaryButton>

                        <DangerButton disabled={processing}>
                            {processing ? "Menghapus..." : "Hapus Permanen"}
                        </DangerButton>
                    </div>
                </form>
            </Modal>
        </section>
    );
}
