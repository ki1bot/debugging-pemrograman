import InputError from "@/Components/GalatInput";
import InputLabel from "@/Components/LabelInput";
import PrimaryButton from "@/Components/TombolUtama";
import TextInput from "@/Components/InputTeks";
import GuestLayout from "@/Layouts/TataLetakTamu";
import { Head, Link, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({
        email: "",
    });

    const submit: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();

        post(route("password.email"));
    };

    return (
        <GuestLayout>
            <Head title="Lupa Password" />

            <span className="nb-badge bg-[#ffbd70]">Pemulihan Akun</span>

            <h1 className="mt-5 text-4xl font-black tracking-[-0.06em]">
                Lupa Password?
            </h1>

            <p className="mt-3 font-semibold leading-7 text-neutral-700">
                Masukkan email akun Anda. Sistem akan mengirimkan tautan
                pengaturan ulang password.
            </p>

            {status && (
                <div className="mt-6 border-[3px] border-black bg-[#9ef0b8] p-4 font-bold shadow-[3px_3px_0_#111]">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="mt-7 space-y-6">
                <div>
                    <InputLabel htmlFor="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        isFocused
                        autoComplete="username"
                        onChange={(event) =>
                            setData("email", event.target.value)
                        }
                    />

                    <InputError message={errors.email} className="mt-3" />
                </div>

                <PrimaryButton className="w-full py-4" disabled={processing}>
                    {processing ? "Mengirim..." : "Kirim Tautan Reset"}
                </PrimaryButton>

                <Link
                    href={route("login")}
                    className="block text-center text-sm font-black underline decoration-2 underline-offset-4"
                >
                    Kembali ke halaman masuk
                </Link>
            </form>
        </GuestLayout>
    );
}
