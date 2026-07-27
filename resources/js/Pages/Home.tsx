import ChallengeCard from "@/Components/ChallengeCard";
import StatCard from "@/Components/StatCard";
import PublicLayout from "@/Layouts/PublicLayout";
import { ChallengeCard as ChallengeCardType } from "@/types";
import { Head, Link } from "@inertiajs/react";

type HomeProps = {
    stats: {
        challenges: number;
        hunters: number;
        completedSubmissions: number;
    };
    featuredChallenges: ChallengeCardType[];
};

const features = [
    {
        number: "01",
        title: "Temukan Kesalahan",
        description:
            "Pilih baris kode yang menjadi sumber masalah berdasarkan hasil analisis Anda.",
        background: "bg-[#ffd93d]",
    },
    {
        number: "02",
        title: "Perbaiki Kode",
        description:
            "Tulis kode perbaikan langsung melalui editor dengan syntax highlighting.",
        background: "bg-[#9ed8ff]",
    },
    {
        number: "03",
        title: "Jelaskan Penyebab",
        description:
            "Jelaskan alasan teknis agar pemahaman Anda tidak berhenti pada menyalin solusi.",
        background: "bg-[#ff9c9c]",
    },
];

export default function Home({ stats, featuredChallenges }: HomeProps) {
    return (
        <PublicLayout>
            <Head title="Platform Tantangan Debugging" />

            <section className="overflow-hidden border-b-[3px] border-black bg-[#fff7e6]">
                <div className="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[1.15fr_0.85fr] lg:px-8 lg:py-24">
                    <div>
                        <div className="mb-6 inline-flex rotate-[-2deg] border-[3px] border-black bg-[#9ef0b8] px-4 py-2 font-black shadow-[4px_4px_0_#111]">
                            BELAJAR DEBUGGING TANPA MENJALANKAN KODE BERBAHAYA
                        </div>

                        <h1 className="page-title text-balance">
                            Buru bug.
                            <br />
                            Perbaiki kode.
                            <br />
                            <span className="inline-block bg-[#ff6b6b] px-2">
                                Pahami sebabnya.
                            </span>
                        </h1>

                        <p className="mt-8 max-w-2xl text-lg font-semibold leading-8 text-neutral-700">
                            BugHunt adalah platform pembelajaran interaktif
                            untuk melatih kemampuan membaca kode, menemukan
                            kesalahan, memperbaiki program, dan menjelaskan
                            penyebab error secara teknis.
                        </p>

                        <div className="mt-9 flex flex-wrap gap-4">
                            <Link
                                href={route("challenges.index")}
                                className="nb-button bg-[#ffd93d] px-6 py-4 text-base"
                            >
                                Jelajahi Tantangan
                            </Link>

                            <Link
                                href={route("register")}
                                className="nb-button bg-[#9ed8ff] px-6 py-4 text-base"
                            >
                                Mulai Berburu
                            </Link>
                        </div>
                    </div>

                    <div className="relative">
                        <div className="nb-card relative z-10 rotate-[2deg] bg-[#111111] p-5 text-white">
                            <div className="mb-4 flex items-center justify-between border-b-2 border-white pb-3">
                                <span className="font-black text-white">
                                    challenge.js
                                </span>

                                <span className="border-2 border-white bg-[#ff6b6b] px-2 py-1 text-xs font-black text-black">
                                    BUG FOUND?
                                </span>
                            </div>

                            <pre className="overflow-x-auto font-mono text-sm leading-8 text-[#f7f7f7]">
                                <code>{`const numbers = [1, 2, 3, 4];

for (let i = 0;
     i <= numbers.length;
     i++) {
    console.log(numbers[i]);
}`}</code>
                            </pre>

                            <div className="mt-5 border-2 border-white bg-[#ffd93d] p-4 text-black">
                                <p className="text-xs font-black uppercase tracking-[0.14em]">
                                    Misi Anda
                                </p>

                                <p className="mt-2 font-black">
                                    Temukan baris bermasalah, perbaiki kode, dan
                                    jelaskan mengapa nilai undefined muncul.
                                </p>
                            </div>
                        </div>

                        <div className="absolute -bottom-6 -left-5 h-32 w-32 rotate-12 border-[3px] border-black bg-[#b7a4ff] shadow-[6px_6px_0_#111]" />

                        <div className="absolute -right-5 -top-6 h-24 w-24 -rotate-12 border-[3px] border-black bg-[#9ef0b8] shadow-[6px_6px_0_#111]" />
                    </div>
                </div>
            </section>

            <section className="border-b-[3px] border-black bg-[#b7a4ff]">
                <div className="mx-auto grid max-w-7xl gap-5 px-4 py-10 sm:grid-cols-3 sm:px-6 lg:px-8">
                    <StatCard
                        label="Tantangan Tersedia"
                        value={stats.challenges}
                        description="JavaScript, PHP, dan SQL"
                        background="bg-[#ffd93d]"
                    />

                    <StatCard
                        label="Bug Hunters"
                        value={stats.hunters}
                        description="Pengguna yang berlatih di BugHunt"
                        background="bg-[#9ed8ff]"
                    />

                    <StatCard
                        label="Tantangan Dituntaskan"
                        value={stats.completedSubmissions}
                        description="Submission dengan status selesai"
                        background="bg-[#9ef0b8]"
                    />
                </div>
            </section>

            <section className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div className="max-w-3xl">
                    <p className="text-sm font-black uppercase tracking-[0.18em]">
                        Alur Pembelajaran
                    </p>

                    <h2 className="section-title mt-3">
                        Tidak sekadar menebak jawaban.
                    </h2>

                    <p className="mt-5 font-semibold leading-7 text-neutral-700">
                        Setiap tantangan memaksa Anda menganalisis lokasi bug,
                        memperbaiki kode, dan menjelaskan akar masalahnya.
                    </p>
                </div>

                <div className="mt-10 grid gap-6 md:grid-cols-3">
                    {features.map((feature) => (
                        <article
                            key={feature.number}
                            className={`nb-card p-6 ${feature.background}`}
                        >
                            <div className="grid h-14 w-14 place-items-center border-[3px] border-black bg-white text-xl font-black shadow-[3px_3px_0_#111]">
                                {feature.number}
                            </div>

                            <h3 className="mt-6 text-2xl font-black">
                                {feature.title}
                            </h3>

                            <p className="mt-4 font-semibold leading-7 text-neutral-800">
                                {feature.description}
                            </p>
                        </article>
                    ))}
                </div>
            </section>

            <section className="border-y-[3px] border-black bg-[#9ed8ff]">
                <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                    <div className="flex flex-col justify-between gap-6 md:flex-row md:items-end">
                        <div>
                            <p className="text-sm font-black uppercase tracking-[0.18em]">
                                Tantangan Terbaru
                            </p>

                            <h2 className="section-title mt-3">
                                Pilih bug pertama Anda.
                            </h2>
                        </div>

                        <Link
                            href={route("challenges.index")}
                            className="nb-button self-start bg-white"
                        >
                            Lihat Semua
                        </Link>
                    </div>

                    {featuredChallenges.length > 0 ? (
                        <div className="mt-10 grid gap-7 md:grid-cols-2 xl:grid-cols-3">
                            {featuredChallenges.map((challenge) => (
                                <ChallengeCard
                                    key={challenge.id}
                                    challenge={challenge}
                                />
                            ))}
                        </div>
                    ) : (
                        <div className="nb-card mt-10 bg-white p-8 text-center">
                            <p className="text-xl font-black">
                                Tantangan belum tersedia.
                            </p>
                        </div>
                    )}
                </div>
            </section>

            <section className="mx-auto max-w-5xl px-4 py-20 text-center sm:px-6 lg:px-8">
                <div className="nb-card rotate-[-1deg] bg-[#ff6b6b] p-8 sm:p-12">
                    <p className="text-sm font-black uppercase tracking-[0.18em]">
                        Siap Menguji Logika?
                    </p>

                    <h2 className="mt-4 text-4xl font-black tracking-[-0.06em] sm:text-6xl">
                        Kesalahan adalah bahan latihan.
                    </h2>

                    <p className="mx-auto mt-6 max-w-2xl text-lg font-semibold leading-8">
                        Buat akun, selesaikan tantangan, kumpulkan poin, dan
                        naikkan posisi Anda di leaderboard.
                    </p>

                    <Link
                        href={route("register")}
                        className="nb-button mt-8 bg-[#ffd93d] px-7 py-4 text-base"
                    >
                        Daftar Sekarang
                    </Link>
                </div>
            </section>
        </PublicLayout>
    );
}
