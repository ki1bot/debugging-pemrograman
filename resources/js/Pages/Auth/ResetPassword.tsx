import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

type ResetPasswordProps = {
    token: string;
    email: string;
};

type ResetPasswordForm = {
    token: string;
    email: string;
    password: string;
    password_confirmation: string;
};

export default function ResetPassword({ token, email }: ResetPasswordProps) {
    const { data, setData, post, processing, errors, reset } =
        useForm<ResetPasswordForm>({
            token,
            email,
            password: "",
            password_confirmation: "",
        });

    const submit: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();

        post(route("password.store"), {
            onFinish: () => reset("password", "password_confirmation"),
        });
    };

    return (
        <GuestLayout>
            <Head title="Atur Ulang Kata Sandi" />

            <span className="nb-badge bg-[#9ed8ff]">Kata Sandi Baru</span>

            <h1 className="mt-5 text-4xl font-black tracking-[-0.06em]">
                Atur ulang kata sandi
            </h1>

            <form onSubmit={submit} className="mt-7 space-y-5">
                <div>
                    <InputLabel htmlFor="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        autoComplete="username"
                        onChange={(event) =>
                            setData("email", event.target.value)
                        }
                    />

                    <InputError message={errors.email} className="mt-3" />
                </div>

                <div>
                    <InputLabel htmlFor="password" value="Kata Sandi Baru" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        isFocused
                        autoComplete="new-password"
                        onChange={(event) =>
                            setData("password", event.target.value)
                        }
                    />

                    <InputError message={errors.password} className="mt-3" />
                </div>

                <div>
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Konfirmasi Kata Sandi"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        autoComplete="new-password"
                        onChange={(event) =>
                            setData("password_confirmation", event.target.value)
                        }
                    />

                    <InputError
                        message={errors.password_confirmation}
                        className="mt-3"
                    />
                </div>

                <PrimaryButton className="w-full py-4" disabled={processing}>
                    {processing
                        ? "Sedang Menyimpan..."
                        : "Simpan Kata Sandi Baru"}
                </PrimaryButton>
            </form>
        </GuestLayout>
    );
}
