import PublicLayout from "@/Layouts/PublicLayout";
import { Head, Link } from "@inertiajs/react";

const objectives = [
    "Meningkatkan kemampuan membaca dan menganalisis kode.",
    "Melatih kemampuan menemukan lokasi dan penyebab error.",
    "Membiasakan pengguna memperbaiki kode secara terstruktur.",
    "Mendorong pemahaman teknis, bukan sekadar menyalin jawaban.",
];

const securityRules = [
    "Kode pengguna tidak dijalankan melalui eval atau terminal server.",
    "Jawaban diperiksa berdasarkan solusi dan alternatif yang disiapkan.",
    "Input kode dan penjelasan dibatasi dan divalidasi.",
    "Solusi tidak ditampilkan sebelum pengguna mengirim jawaban.",
];

export default function About() {
    return (
        <PublicLayout>
            <Head title="Tentang BugHunt" />

            <section className="border-b-[3px] border-black bg-[#ffd93d]">
                <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <p className="text-sm font-black uppercase tracking-[0.18em]">
                        Tentang Platform
                    </p>

                    <h1 className="page-title mt-4 max-w-5xl">
                        Debugging adalah kemampuan membaca masalah, bukan
                        sekadar mencoba-coba kode.
                    </h1>
                </div>
            </section>

            <section className="mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8">
                <article className="nb-card bg-white p-7">
                    <span className="nb-badge bg-[#9ed8ff]">
                        Latar Belakang
                    </span>

                    <h2 className="mt-6 text-3xl font-black tracking-[-0.04em]">
                        Mengapa BugHunt dibuat?
                    </h2>

                    <div className="mt-5 space-y-4 font-semibold leading-8 text-neutral-700">
                        <p>
                            Banyak pemula dapat menyalin kode yang benar tetapi
                            belum memahami mengapa kode sebelumnya gagal.
                            Akibatnya, mereka kembali mengalami kesulitan ketika
                            menemui error yang sedikit berbeda.
                        </p>

                        <p>
                            BugHunt mengubah proses debugging menjadi latihan
                            bertahap. Pengguna harus menentukan baris
                            bermasalah, menulis perbaikan, lalu menjelaskan akar
                            kesalahan.
                        </p>
                    </div>
                </article>

                <article className="nb-card bg-[#b7a4ff] p-7">
                    <span className="nb-badge bg-white">Tujuan</span>

                    <h2 className="mt-6 text-3xl font-black tracking-[-0.04em]">
                        Kemampuan yang dilatih
                    </h2>

                    <div className="mt-6 grid gap-4">
                        {objectives.map((objective, index) => (
                            <div
                                key={objective}
                                className="flex items-start gap-4 border-[3px] border-black bg-white p-4 shadow-[3px_3px_0_#111]"
                            >
                                <span className="grid h-9 w-9 shrink-0 place-items-center border-2 border-black bg-[#ffd93d] font-black">
                                    {index + 1}
                                </span>

                                <p className="font-bold leading-7">
                                    {objective}
                                </p>
                            </div>
                        ))}
                    </div>
                </article>
            </section>

            <section className="border-y-[3px] border-black bg-[#111111] text-white">
                <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <div className="grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
                        <div>
                            <p className="text-sm font-black uppercase tracking-[0.18em] text-[#ffd93d]">
                                Keamanan
                            </p>

                            <h2 className="section-title mt-4">
                                Tidak ada eksekusi kode pengguna.
                            </h2>

                            <p className="mt-6 font-semibold leading-8 text-neutral-300">
                                BugHunt menggunakan validasi berbasis solusi
                                yang telah disiapkan administrator. Pendekatan
                                ini membatasi ruang lingkup dan menghindari
                                risiko menjalankan kode asing di server.
                            </p>
                        </div>

                        <div className="grid gap-4 sm:grid-cols-2">
                            {securityRules.map((rule) => (
                                <article
                                    key={rule}
                                    className="border-[3px] border-white bg-[#2a2a2a] p-5 shadow-[5px_5px_0_#ffd93d]"
                                >
                                    <p className="font-bold leading-7">
                                        {rule}
                                    </p>
                                </article>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            <section className="mx-auto max-w-5xl px-4 py-20 text-center sm:px-6 lg:px-8">
                <div className="nb-card bg-[#9ef0b8] p-8 sm:p-12">
                    <h2 className="text-4xl font-black tracking-[-0.05em]">
                        Mulai dari satu bug.
                    </h2>

                    <p className="mx-auto mt-5 max-w-2xl font-semibold leading-8">
                        Tantangan tersedia untuk JavaScript, PHP, dan SQL dengan
                        tingkat mudah, menengah, serta sulit.
                    </p>

                    <Link
                        href={route("challenges.index")}
                        className="nb-button mt-7 bg-[#ffd93d]"
                    >
                        Buka Daftar Tantangan
                    </Link>
                </div>
            </section>
        </PublicLayout>
    );
}
