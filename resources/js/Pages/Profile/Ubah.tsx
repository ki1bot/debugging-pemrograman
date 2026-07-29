import AuthenticatedLayout from "@/Layouts/TataLetakTerautentikasi";
import { PageProps } from "@/types";
import { Head } from "@inertiajs/react";
import DeleteUserForm from "./Partials/FormulirHapusPengguna";
import UpdatePasswordForm from "./Partials/FormulirUbahKataSandi";
import UpdateProfileInformationForm from "./Partials/FormulirUbahInformasiProfil";

export default function Edit({
    mustVerifyEmail,
    status,
}: PageProps<{
    mustVerifyEmail: boolean;
    status?: string;
}>) {
    return (
        <AuthenticatedLayout
            header={
                <div>
                    <p className="text-sm font-black uppercase tracking-[0.18em]">
                        Pengaturan Akun
                    </p>

                    <h1 className="mt-2 text-4xl font-black tracking-[-0.05em]">
                        Profil Pengguna
                    </h1>
                </div>
            }
        >
            <Head title="Profil" />

            <div className="mx-auto max-w-5xl space-y-8 px-4 py-10 sm:px-6 lg:px-8">
                <div className="nb-card bg-white p-6 sm:p-8">
                    <UpdateProfileInformationForm
                        mustVerifyEmail={mustVerifyEmail}
                        status={status}
                    />
                </div>

                <div className="nb-card bg-[#9ed8ff] p-6 sm:p-8">
                    <UpdatePasswordForm />
                </div>

                <div className="nb-card bg-[#ff9c9c] p-6 sm:p-8">
                    <DeleteUserForm />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
