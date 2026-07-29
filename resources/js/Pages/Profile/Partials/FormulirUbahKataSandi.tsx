import InputError from '@/Components/GalatInput';
import InputLabel from '@/Components/LabelInput';
import PrimaryButton from '@/Components/TombolUtama';
import TextInput from '@/Components/InputTeks';
import { Transition } from '@headlessui/react';
import { useForm } from '@inertiajs/react';
import {
    FormEventHandler,
    useRef,
} from 'react';

type PasswordForm = {
    current_password: string;
    password: string;
    password_confirmation: string;
};

export default function UpdatePasswordForm({
    className = '',
}: {
    className?: string;
}) {
    const currentPasswordInput =
        useRef<HTMLInputElement>(null);

    const passwordInput =
        useRef<HTMLInputElement>(null);

    const {
        data,
        setData,
        errors,
        put,
        reset,
        processing,
        recentlySuccessful,
    } = useForm<PasswordForm>({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const updatePassword: FormEventHandler<HTMLFormElement> = (
        event,
    ) => {
        event.preventDefault();

        put(route('password.update'), {
            preserveScroll: true,
            onSuccess: () => reset(),
            onError: (formErrors) => {
                if (formErrors.password) {
                    reset(
                        'password',
                        'password_confirmation',
                    );

                    passwordInput.current?.focus();
                }

                if (
                    formErrors.current_password
                ) {
                    reset('current_password');

                    currentPasswordInput.current?.focus();
                }
            },
        });
    };

    return (
        <section className={className}>
            <span className="nb-badge bg-white">
                Keamanan
            </span>

            <h2 className="mt-5 text-2xl font-black tracking-[-0.04em]">
                Ubah Password
            </h2>

            <p className="mt-3 font-semibold leading-7 text-neutral-700">
                Gunakan password yang panjang, unik, dan
                tidak dipakai pada layanan lain.
            </p>

            <form
                onSubmit={updatePassword}
                className="mt-7 space-y-6"
            >
                <div>
                    <InputLabel
                        htmlFor="current_password"
                        value="Password Saat Ini"
                    />

                    <TextInput
                        id="current_password"
                        ref={currentPasswordInput}
                        value={data.current_password}
                        onChange={(event) =>
                            setData(
                                'current_password',
                                event.target.value,
                            )
                        }
                        type="password"
                        autoComplete="current-password"
                    />

                    <InputError
                        message={
                            errors.current_password
                        }
                        className="mt-3"
                    />
                </div>

                <div>
                    <InputLabel
                        htmlFor="password"
                        value="Password Baru"
                    />

                    <TextInput
                        id="password"
                        ref={passwordInput}
                        value={data.password}
                        onChange={(event) =>
                            setData(
                                'password',
                                event.target.value,
                            )
                        }
                        type="password"
                        autoComplete="new-password"
                    />

                    <InputError
                        message={errors.password}
                        className="mt-3"
                    />
                </div>

                <div>
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Konfirmasi Password Baru"
                    />

                    <TextInput
                        id="password_confirmation"
                        value={
                            data.password_confirmation
                        }
                        onChange={(event) =>
                            setData(
                                'password_confirmation',
                                event.target.value,
                            )
                        }
                        type="password"
                        autoComplete="new-password"
                    />

                    <InputError
                        message={
                            errors.password_confirmation
                        }
                        className="mt-3"
                    />
                </div>

                <div className="flex flex-wrap items-center gap-5">
                    <PrimaryButton
                        disabled={processing}
                    >
                        {processing
                            ? 'Menyimpan...'
                            : 'Ubah Password'}
                    </PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition duration-200"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="transition duration-200"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                    >
                        <p className="border-2 border-black bg-[#9ef0b8] px-3 py-2 text-sm font-black shadow-[2px_2px_0_#111]">
                            Password berhasil diubah.
                        </p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
