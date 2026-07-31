import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import { PageProps } from "@/types";
import { Transition } from "@headlessui/react";
import { Link, useForm, usePage } from "@inertiajs/react";
import { FormEventHandler } from "react";

export default function UpdateProfileInformation({
    mustVerifyEmail,
    status,
    className = "",
}: {
    mustVerifyEmail: boolean;
    status?: string;
    className?: string;
}) {
    const user = usePage<PageProps>().props.auth.user;

    const { data, setData, patch, errors, processing, recentlySuccessful } =
        useForm({
            name: user?.name ?? "",
            email: user?.email ?? "",
        });

    const submit: FormEventHandler<HTMLFormElement> = (event) => {
        event.preventDefault();

        patch(route("profile.update"));
    };

    if (!user) {
        return null;
    }

    return (
        <section className={className}>
            <header>
                <span className="nb-badge bg-[#9ed8ff]">Informasi Akun</span>

                <h2 className="mt-5 text-2xl font-black tracking-[-0.04em]">
                    Informasi Profil
                </h2>

                <p className="mt-3 font-semibold leading-7 text-neutral-700">
                    Perbarui nama dan alamat email yang digunakan pada akun
                    Debugging Pemrograman.
                </p>
            </header>

            <form onSubmit={submit} className="mt-7 space-y-6">
                <div>
                    <InputLabel htmlFor="name" value="Nama" />

                    <TextInput
                        id="name"
                        className="mt-1 block w-full"
                        value={data.name}
                        onChange={(event) =>
                            setData("name", event.target.value)
                        }
                        required
                        isFocused
                        autoComplete="name"
                    />

                    <InputError className="mt-3" message={errors.name} />
                </div>

                <div>
                    <InputLabel htmlFor="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        className="mt-1 block w-full"
                        value={data.email}
                        onChange={(event) =>
                            setData("email", event.target.value)
                        }
                        required
                        autoComplete="username"
                    />

                    <InputError className="mt-3" message={errors.email} />
                </div>

                {mustVerifyEmail && user.email_verified_at === null && (
                    <div className="border-[3px] border-black bg-[#fff1a8] p-4 shadow-[3px_3px_0_#111]">
                        <p className="font-semibold leading-7">
                            Alamat emailmu belum diverifikasi.
                        </p>

                        <Link
                            href={route("verification.send")}
                            method="post"
                            as="button"
                            className="mt-3 inline-flex font-black underline decoration-2 underline-offset-4"
                        >
                            Kirim ulang email verifikasi
                        </Link>

                        {status === "verification-link-sent" && (
                            <div className="mt-4 border-2 border-black bg-[#9ef0b8] p-3 font-bold">
                                Tautan verifikasi baru telah dikirim ke alamat
                                emailmu.
                            </div>
                        )}
                    </div>
                )}

                <div className="flex flex-wrap items-center gap-5">
                    <PrimaryButton disabled={processing}>
                        {processing ? "Sedang Menyimpan..." : "Simpan Profil"}
                    </PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition duration-200 ease-out"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="transition duration-200 ease-in"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                    >
                        <p className="border-2 border-black bg-[#9ef0b8] px-3 py-2 text-sm font-black shadow-[2px_2px_0_#111]">
                            Profil berhasil disimpan.
                        </p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
