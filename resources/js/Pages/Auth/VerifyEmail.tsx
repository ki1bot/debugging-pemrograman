import PrimaryButton from "@/Components/PrimaryButton";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

export default function VerifyEmail({ status }: { status?: string }) {
    const { post, processing } = useForm({});

    const submit: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();

        post(route("verification.send"));
    };

    return (
        <GuestLayout>
            <Head title="Verifikasi Email" />

            <span className="nb-badge bg-[#9ef0b8]">Verifikasi Akun</span>

            <h1 className="mt-5 text-3xl font-black tracking-[-0.05em]">
                Periksa Email Anda
            </h1>

            <p className="mt-4 font-semibold leading-7 text-neutral-700">
                Klik tautan verifikasi yang sudah dikirimkan. Tautan baru dapat
                dikirim apabila email sebelumnya tidak diterima.
            </p>

            {status === "verification-link-sent" && (
                <div className="mt-6 border-[3px] border-black bg-[#9ef0b8] p-4 font-bold shadow-[3px_3px_0_#111]">
                    Tautan verifikasi baru telah dikirim.
                </div>
            )}

            <form onSubmit={submit} className="mt-7">
                <PrimaryButton className="w-full py-4" disabled={processing}>
                    Kirim Ulang Tautan
                </PrimaryButton>
            </form>

            <Link
                href={route("logout")}
                method="post"
                as="button"
                className="mt-5 block w-full text-center text-sm font-black underline decoration-2 underline-offset-4"
            >
                Keluar
            </Link>
        </GuestLayout>
    );
}
