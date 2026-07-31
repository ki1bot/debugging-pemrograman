import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors, reset } = useForm({
        password: "",
    });

    const submit: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();

        post(route("password.confirm"), {
            onFinish: () => reset("password"),
        });
    };

    return (
        <GuestLayout>
            <Head title="Konfirmasi Kata Sandi" />

            <span className="nb-badge bg-[#ffbd70]">Area Terlindungi</span>

            <h1 className="mt-5 text-3xl font-black tracking-[-0.05em]">
                Konfirmasi kata sandi
            </h1>

            <p className="mt-3 font-semibold leading-7 text-neutral-700">
                Masukkan kembali kata sandimu sebelum melanjutkan tindakan yang
                memerlukan verifikasi.
            </p>

            <form onSubmit={submit} className="mt-7 space-y-6">
                <div>
                    <InputLabel htmlFor="password" value="Kata Sandi" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        isFocused
                        autoComplete="current-password"
                        onChange={(event) =>
                            setData("password", event.target.value)
                        }
                    />

                    <InputError message={errors.password} className="mt-3" />
                </div>

                <PrimaryButton className="w-full py-4" disabled={processing}>
                    {processing ? "Sedang Memeriksa..." : "Konfirmasi"}
                </PrimaryButton>
            </form>
        </GuestLayout>
    );
}
