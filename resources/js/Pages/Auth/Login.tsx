import Checkbox from "@/Components/Checkbox";
import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

type LoginForm = {
    email: string;
    password: string;
    remember: boolean;
};

export default function Login({
    status,
    canResetPassword,
}: {
    status?: string;
    canResetPassword: boolean;
}) {
    const { data, setData, post, processing, errors, reset } =
        useForm<LoginForm>({
            email: "",
            password: "",
            remember: false,
        });

    const submit: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();

        post(route("login"), {
            onFinish: () => reset("password"),
        });
    };

    return (
        <GuestLayout>
            <Head title="Masuk" />

            <span className="nb-badge bg-[#9ed8ff]">
                Selamat Datang Kembali
            </span>

            <h1 className="mt-5 text-4xl font-black tracking-[-0.06em]">
                Masuk ke akunmu
            </h1>

            <p className="mt-3 font-semibold leading-7 text-neutral-700">
                Masuk untuk melanjutkan tantangan dan melihat perkembangan
                latihan yang sudah kamu kumpulkan.
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
                        required
                        autoComplete="username"
                        onChange={(event) =>
                            setData("email", event.target.value)
                        }
                    />

                    <InputError message={errors.email} className="mt-3" />
                </div>

                <div>
                    <InputLabel htmlFor="password" value="Kata Sandi" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        required
                        autoComplete="current-password"
                        showPasswordToggle
                        onChange={(event) =>
                            setData("password", event.target.value)
                        }
                    />

                    <InputError message={errors.password} className="mt-3" />
                </div>

                <div className="flex items-start gap-3">
                    <Checkbox
                        id="remember"
                        name="remember"
                        checked={data.remember}
                        onChange={(event) =>
                            setData("remember", event.target.checked)
                        }
                        className="mt-1 shrink-0"
                    />

                    <label
                        htmlFor="remember"
                        className="cursor-pointer select-none"
                    >
                        <span className="block font-black">
                            Tetap masuk di perangkat ini
                        </span>
                    </label>
                </div>

                <PrimaryButton className="w-full py-4" disabled={processing}>
                    {processing ? "Sedang Masuk..." : "Masuk"}
                </PrimaryButton>

                <div className="grid gap-3 text-center text-sm font-bold">
                    {canResetPassword && (
                        <Link
                            href={route("password.request")}
                            className="underline decoration-2 underline-offset-4"
                        >
                            Lupa kata sandi?
                        </Link>
                    )}

                    <p>
                        Belum memiliki akun?{" "}
                        <Link
                            href={route("register")}
                            className="font-black underline decoration-2 underline-offset-4"
                        >
                            Buat akun
                        </Link>
                    </p>
                </div>
            </form>
        </GuestLayout>
    );
}
