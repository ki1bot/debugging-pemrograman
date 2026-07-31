import PublicLayout from "@/Layouts/PublicLayout";
import { Head, Link } from "@inertiajs/react";

const objectives = [
    "Membaca dan memahami alur kode sebelum melakukan perubahan.",
    "Menemukan bagian yang menyebabkan error secara lebih terarah.",
    "Memperbaiki kode berdasarkan penyebab masalah, bukan sekadar mencoba-coba.",
    "Menjelaskan penyebab bug dan alasan teknis di balik perbaikannya.",
];

const securityRules = [
    "Kode uji dikirim ke layanan sandbox terisolasi, bukan dijalankan langsung oleh aplikasi.",
    "Penilaian jawaban tetap dibandingkan dengan solusi dan alternatif yang sudah disiapkan.",
    "Kode, input, dan penjelasan yang dikirim dibatasi serta divalidasi.",
    "Pembahasan lengkap baru terbuka setelah tantangan berhasil diselesaikan.",
];

export default function About() {
    return (
        <PublicLayout>
            <Head title="Tentang Debugging Pemrograman" />

            <section className="border-b-[3px] border-black bg-[#ffd93d]">
                <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <p className="text-sm font-black uppercase tracking-[0.18em]">
                        Tentang Debugging Pemrograman
                    </p>

                    <h1 className="page-title mt-4 max-w-5xl">
                        Debugging bukan sekadar mengubah kode sampai berhasil.
                        Kamu perlu memahami letak masalah, penyebabnya, dan
                        alasan perbaikannya.
                    </h1>
                </div>
            </section>

            <section className="mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8">
                <article className="nb-card bg-white p-7">
                    <span className="nb-badge bg-[#9ed8ff]">
                        Latar Belakang
                    </span>

                    <h2 className="mt-6 text-3xl font-black tracking-[-0.04em]">
                        Mengapa platform ini dibuat?
                    </h2>

                    <div className="mt-5 space-y-4 font-semibold leading-8 text-neutral-700">
                        <p>
                            Menyalin kode yang sudah benar mungkin dapat
                            menyelesaikan satu error. Namun, cara tersebut tidak
                            membantu ketika kamu menghadapi masalah lain dengan
                            bentuk yang berbeda.
                        </p>

                        <p>
                            Di Debugging Pemrograman, kamu belajar dari kode
                            yang memang memiliki bug. Kamu perlu menemukan
                            sumber masalah, menulis perbaikannya, menguji
                            hasilnya, dan menjelaskan mengapa perubahan tersebut
                            bekerja.
                        </p>
                    </div>
                </article>

                <article className="nb-card bg-[#b7a4ff] p-7">
                    <span className="nb-badge bg-white">Kemampuan Utama</span>

                    <h2 className="mt-6 text-3xl font-black tracking-[-0.04em]">
                        Kemampuan yang akan kamu latih
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
                                Keamanan Eksekusi
                            </p>

                            <h2 className="section-title mt-4">
                                Kode diuji melalui sandbox yang terisolasi.
                            </h2>

                            <p className="mt-6 font-semibold leading-8 text-neutral-300">
                                Kode yang kamu jalankan dikirim ke layanan
                                eksekusi terpisah. Sementara itu, penilaian
                                akhir tetap dilakukan dengan membandingkan
                                jawaban terhadap solusi yang telah disiapkan.
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
                        Mulai dengan satu tantangan.
                    </h2>

                    <p className="mx-auto mt-5 max-w-2xl font-semibold leading-8">
                        Pilih bahasa pemrograman dan tingkat kesulitan yang
                        sesuai, lalu selesaikan masalahnya secara bertahap.
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
