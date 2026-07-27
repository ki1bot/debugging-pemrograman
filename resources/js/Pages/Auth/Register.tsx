import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

type RegisterForm = {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
};

export default function Register() {
    const { data, setData, post, processing, errors, reset } =
        useForm<RegisterForm>({
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
        });

    const submit: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();

        post(route("register"), {
            onFinish: () => reset("password", "password_confirmation"),
        });
    };

    return (
        <GuestLayout>
            <Head title="Daftar" />

            <span className="nb-badge bg-[#9ef0b8]">Akun Baru</span>

            <h1 className="mt-5 text-4xl font-black tracking-[-0.06em]">
                Jadi Bug Hunter
            </h1>

            <p className="mt-3 font-semibold leading-7 text-neutral-700">
                Buat akun untuk menyimpan poin, progres, riwayat pengerjaan, dan
                posisi leaderboard.
            </p>

            <form onSubmit={submit} className="mt-7 space-y-5">
                <div>
                    <InputLabel htmlFor="name" value="Nama" />

                    <TextInput
                        id="name"
                        name="name"
                        value={data.name}
                        isFocused
                        autoComplete="name"
                        onChange={(event) =>
                            setData("name", event.target.value)
                        }
                    />

                    <InputError message={errors.name} className="mt-3" />
                </div>

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
                    <InputLabel htmlFor="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
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
                        value="Konfirmasi Password"
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
                    {processing ? "Membuat Akun..." : "Daftar"}
                </PrimaryButton>

                <p className="text-center text-sm font-bold">
                    Sudah memiliki akun?{" "}
                    <Link
                        href={route("login")}
                        className="font-black underline decoration-2 underline-offset-4"
                    >
                        Masuk
                    </Link>
                </p>
            </form>
        </GuestLayout>
    );
}
