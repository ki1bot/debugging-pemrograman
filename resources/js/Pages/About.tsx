import PublicLayout from "@/Layouts/PublicLayout";
import { Head, Link } from "@inertiajs/react";

const objectives = [
    "Terbiasa membaca alur kode sebelum mengubahnya.",
    "Lebih cepat menemukan bagian yang menyebabkan error.",
    "Memperbaiki kode dengan langkah yang jelas, bukan asal mencoba.",
    "Menjelaskan penyebab bug dengan alasan teknis yang masuk akal.",
];

const securityRules = [
    "Kode jawaban tidak dijalankan dengan eval atau lewat terminal server.",
    "Jawaban dibandingkan dengan solusi dan alternatif yang sudah disiapkan.",
    "Kode dan penjelasan yang dikirim dibatasi serta divalidasi.",
    "Pembahasan baru terbuka setelah tantangan berhasil diselesaikan.",
];

export default function About() {
    return (
        <PublicLayout>
            <Head title="Tentang BugHunt" />

            <section className="border-b-[3px] border-black bg-[#ffd93d]">
                <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <p className="text-sm font-black uppercase tracking-[0.18em]">
                        Tentang BugHunt
                    </p>

                    <h1 className="page-title mt-4 max-w-5xl">
                        Debugging bukan soal mengganti kode sampai jalan. Yang
                        penting adalah memahami letak dan penyebab masalahnya.
                    </h1>
                </div>
            </section>

            <section className="mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8">
                <article className="nb-card bg-white p-7">
                    <span className="nb-badge bg-[#9ed8ff]">
                        Latar Belakang
                    </span>

                    <h2 className="mt-6 text-3xl font-black tracking-[-0.04em]">
                        Kenapa BugHunt dibuat?
                    </h2>

                    <div className="mt-5 space-y-4 font-semibold leading-8 text-neutral-700">
                        <p>
                            Menyalin kode yang benar memang dapat menyelesaikan
                            error untuk sementara. Masalahnya, cara tersebut
                            tidak membantu ketika bentuk error berikutnya
                            sedikit berbeda.
                        </p>

                        <p>
                            Di BugHunt, kamu belajar dari kode yang memang
                            memiliki bug. Kamu harus mencari bagian yang salah,
                            menulis perbaikannya, lalu menjelaskan kenapa bug
                            tersebut bisa terjadi.
                        </p>
                    </div>
                </article>

                <article className="nb-card bg-[#b7a4ff] p-7">
                    <span className="nb-badge bg-white">Yang Dilatih</span>

                    <h2 className="mt-6 text-3xl font-black tracking-[-0.04em]">
                        Kemampuan yang akan kamu gunakan
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
                                Kode jawaban tidak dijalankan di server.
                            </h2>

                            <p className="mt-6 font-semibold leading-8 text-neutral-300">
                                BugHunt memeriksa jawaban dengan
                                membandingkannya terhadap solusi yang sudah
                                disiapkan. Dengan cara ini, server tidak perlu
                                menjalankan kode asing yang dikirim pengguna.
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
                        Coba satu tantangan dulu.
                    </h2>

                    <p className="mx-auto mt-5 max-w-2xl font-semibold leading-8">
                        Kamu dapat memilih latihan JavaScript, PHP, atau SQL
                        dengan tingkat mudah, menengah, dan sulit.
                    </p>

                    <Link
                        href={route("challenges.index")}
                        className="nb-button mt-7 bg-[#ffd93d]"
                    >
                        Lihat Daftar Tantangan
                    </Link>
                </div>
            </section>
        </PublicLayout>
    );
}
