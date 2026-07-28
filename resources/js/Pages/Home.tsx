import ChallengeCard from "@/Components/ChallengeCard";
import StatCard from "@/Components/StatCard";
import PublicLayout from "@/Layouts/PublicLayout";
import { ChallengeCard as ChallengeCardType } from "@/types";
import { Head, Link } from "@inertiajs/react";
import {
    ArrowRight,
    Braces,
    Bug,
    CheckCircle2,
    Code2,
    Lightbulb,
    RotateCcw,
    SearchCode,
    ShieldCheck,
    Sparkles,
    Target,
    Trophy,
    Users,
    Wrench,
} from "lucide-react";
import type { LucideIcon } from "lucide-react";
import { useState } from "react";

type HomeProps = {
    stats: {
        challenges: number;
        hunters: number;
        completedSubmissions: number;
    };
    featuredChallenges: ChallengeCardType[];
};

type Feature = {
    number: string;
    title: string;
    description: string;
    icon: LucideIcon;
    background: string;
    iconBackground: string;
};

type PreviewMode = "idle" | "answer" | "hint";

const features: Feature[] = [
    {
        number: "01",
        title: "Temukan akar masalah",
        description:
            "Analisis struktur program dan identifikasi baris yang menyebabkan perilaku tidak sesuai.",
        icon: SearchCode,
        background: "bg-[#fff8d9]",
        iconBackground: "bg-gradient-to-br from-[#ffc84a] to-[#ff9b67]",
    },
    {
        number: "02",
        title: "Tulis perbaikan",
        description:
            "Perbaiki kode langsung melalui editor dengan syntax highlighting yang nyaman digunakan.",
        icon: Wrench,
        background: "bg-[#e8f6ff]",
        iconBackground: "bg-gradient-to-br from-[#8dd4fa] to-[#9c88f7]",
    },
    {
        number: "03",
        title: "Jelaskan penyebab",
        description:
            "Bangun pemahaman teknis dengan menjelaskan penyebab error, bukan sekadar menyalin jawaban.",
        icon: Lightbulb,
        background: "bg-[#ffe8f2]",
        iconBackground: "bg-gradient-to-br from-[#ff9b67] to-[#f56eb3]",
    },
];

export default function Home({ stats, featuredChallenges }: HomeProps) {
    const [previewMode, setPreviewMode] = useState<PreviewMode>("idle");

    const handleCheckAnswer = () => {
        setPreviewMode((currentMode) =>
            currentMode === "answer" ? "idle" : "answer",
        );
    };

    const handleToggleHint = () => {
        setPreviewMode((currentMode) =>
            currentMode === "hint" ? "idle" : "hint",
        );
    };

    return (
        <PublicLayout>
            <Head title="Platform Tantangan Debugging" />

            <section className="neo-grid-background relative overflow-hidden">
                <div
                    aria-hidden="true"
                    className="neo-float absolute left-[4%] top-24 hidden h-12 w-12 rotate-12 rounded-xl border-2 border-[#21162f] bg-[#8dd4fa] shadow-[4px_4px_0_#21162f] xl:block"
                />

                <div
                    aria-hidden="true"
                    className="neo-float-delayed absolute right-[5%] top-28 hidden h-10 w-10 -rotate-12 rounded-full border-2 border-[#21162f] bg-[#ffc84a] shadow-[4px_4px_0_#21162f] xl:block"
                />

                <div className="mx-auto grid max-w-7xl items-center gap-14 px-4 py-16 sm:px-6 lg:grid-cols-[1.02fr_0.98fr] lg:px-8 lg:py-24">
                    <div className="relative z-10">
                        <div className="mb-7 inline-flex items-center gap-2 rounded-full border-2 border-[#21162f] bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.1em] shadow-[3px_4px_0_#21162f] sm:text-sm">
                            <ShieldCheck
                                className="h-4 w-4"
                                strokeWidth={2.8}
                            />
                            Belajar debugging secara aman
                        </div>

                        <h1 className="page-title text-balance text-[#21162f]">
                            Latih logika.
                            <br />
                            Temukan{" "}
                            <span className="neo-gradient-text">bug</span>.
                            <br />
                            Kuasai solusinya.
                        </h1>

                        <p className="mt-7 max-w-2xl text-base font-semibold leading-8 text-[#665f73] sm:text-lg">
                            BugHunt membantu Anda melatih kemampuan membaca
                            kode, menemukan kesalahan, memperbaiki program, dan
                            menjelaskan akar masalah secara teknis.
                        </p>

                        <div className="mt-8 grid max-w-xl gap-3 sm:grid-cols-2">
                            <div className="flex items-center gap-3 text-sm font-extrabold text-[#4f4859]">
                                <CheckCircle2
                                    className="h-5 w-5 text-[#17a56d]"
                                    strokeWidth={2.8}
                                />
                                Analisis tanpa kode berbahaya
                            </div>

                            <div className="flex items-center gap-3 text-sm font-extrabold text-[#4f4859]">
                                <CheckCircle2
                                    className="h-5 w-5 text-[#17a56d]"
                                    strokeWidth={2.8}
                                />
                                Editor dengan syntax highlighting
                            </div>

                            <div className="flex items-center gap-3 text-sm font-extrabold text-[#4f4859]">
                                <CheckCircle2
                                    className="h-5 w-5 text-[#17a56d]"
                                    strokeWidth={2.8}
                                />
                                Penilaian dan leaderboard
                            </div>

                            <div className="flex items-center gap-3 text-sm font-extrabold text-[#4f4859]">
                                <CheckCircle2
                                    className="h-5 w-5 text-[#17a56d]"
                                    strokeWidth={2.8}
                                />
                                Tantangan berbagai tingkat
                            </div>
                        </div>

                        <div className="mt-9 flex flex-wrap gap-4">
                            <Link
                                href={route("challenges.index")}
                                className="nb-button nb-button-primary px-6 py-4 text-base"
                            >
                                Jelajahi Tantangan
                                <ArrowRight
                                    className="h-5 w-5"
                                    strokeWidth={2.8}
                                />
                            </Link>

                            <Link
                                href={route("register")}
                                className="nb-button nb-button-secondary px-6 py-4 text-base"
                            >
                                <Code2 className="h-5 w-5" strokeWidth={2.8} />
                                Mulai Berlatih
                            </Link>
                        </div>
                    </div>

                    <div className="relative mx-auto w-full max-w-xl">
                        <div
                            aria-hidden="true"
                            className="absolute -left-5 top-16 hidden h-20 w-20 rotate-12 rounded-2xl border-2 border-[#21162f] bg-[#ffc84a] shadow-[6px_6px_0_#21162f] sm:block"
                        />

                        <div
                            aria-hidden="true"
                            className="absolute -right-4 bottom-14 hidden h-16 w-16 -rotate-12 rounded-full border-2 border-[#21162f] bg-[#8dd4fa] shadow-[5px_5px_0_#21162f] sm:block"
                        />

                        <div className="nb-card relative z-10 overflow-visible bg-white p-5 sm:p-7">
                            <div className="flex items-center justify-between gap-4 border-b border-[#21162f]/10 pb-5">
                                <div className="flex items-center gap-3">
                                    <span className="neo-icon-box h-12 w-12 bg-gradient-to-br from-[#ffc84a] via-[#ff9b67] to-[#f56eb3]">
                                        <Bug
                                            className="h-6 w-6"
                                            strokeWidth={2.8}
                                        />
                                    </span>

                                    <div>
                                        <p className="font-black tracking-[-0.025em] text-[#21162f]">
                                            Interactive challenge
                                        </p>

                                        <p className="text-xs font-bold text-[#777080]">
                                            challenge.js
                                        </p>
                                    </div>
                                </div>

                                <span className="nb-badge bg-[#d4f8e5]">
                                    Ready
                                </span>
                            </div>

                            <div className="mt-6 overflow-hidden rounded-2xl border-2 border-[#21162f] bg-[#17111f] shadow-[5px_6px_0_#21162f]">
                                <div className="flex items-center justify-between border-b border-white/10 px-4 py-3">
                                    <div className="flex gap-2">
                                        <span className="h-3 w-3 rounded-full bg-[#ff6b7a]" />
                                        <span className="h-3 w-3 rounded-full bg-[#ffc84a]" />
                                        <span className="h-3 w-3 rounded-full bg-[#62d89d]" />
                                    </div>

                                    <Braces
                                        className="h-4 w-4 text-white/60"
                                        strokeWidth={2.5}
                                    />
                                </div>

                                <pre className="overflow-x-auto px-5 py-6 font-mono text-xs leading-7 text-[#f8f5fb] sm:text-sm">
                                    <code>
                                        <span className="text-[#f9a8d4]">
                                            const
                                        </span>{" "}
                                        numbers ={" "}
                                        <span className="text-[#93c5fd]">
                                            [1, 2, 3, 4]
                                        </span>
                                        ;{"\n\n"}
                                        <span className="text-[#f9a8d4]">
                                            for
                                        </span>{" "}
                                        (
                                        <span className="text-[#f9a8d4]">
                                            let
                                        </span>{" "}
                                        i ={" "}
                                        <span className="text-[#facc15]">
                                            0
                                        </span>
                                        ;{"\n"}
                                        {"     "}
                                        <span
                                            className={`rounded px-1 transition-colors duration-300 ${
                                                previewMode === "answer"
                                                    ? "bg-[#91e7bf]/25 text-[#91e7bf]"
                                                    : "bg-[#f56eb3]/25 text-[#fda4d5]"
                                            }`}
                                        >
                                            {previewMode === "answer"
                                                ? "i < numbers.length"
                                                : "i <= numbers.length"}
                                        </span>
                                        ;{"\n"}
                                        {"     "}i++) {"{"}
                                        {"\n"}
                                        {"    "}console.log(numbers[i]);
                                        {"\n"}
                                        {"}"}
                                    </code>
                                </pre>
                            </div>

                            <div className="mt-6 rounded-2xl border border-[#21162f]/10 bg-[#f8f6fc] p-5">
                                <p className="text-xs font-black uppercase tracking-[0.13em] text-[#8d5bc1]">
                                    Misi Anda
                                </p>

                                <p className="mt-2 font-extrabold leading-7 text-[#21162f]">
                                    Temukan penyebab nilai undefined, perbaiki
                                    perulangan, lalu jelaskan akar masalahnya.
                                </p>
                            </div>

                            {previewMode === "answer" && (
                                <div
                                    id="preview-feedback"
                                    aria-live="polite"
                                    className="mt-5 rounded-2xl border-2 border-[#21162f] bg-[#d4f8e5] p-4 shadow-[4px_4px_0_#21162f]"
                                >
                                    <div className="flex items-start gap-3">
                                        <span className="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-xl border-2 border-[#21162f] bg-white">
                                            <CheckCircle2
                                                className="h-5 w-5 text-[#13885c]"
                                                strokeWidth={2.8}
                                            />
                                        </span>

                                        <div>
                                            <p className="font-black text-[#21162f]">
                                                Bug berhasil ditemukan
                                            </p>

                                            <p className="mt-1 text-sm font-semibold leading-6 text-[#4f665b]">
                                                Kondisi{" "}
                                                <code className="rounded bg-white px-1.5 py-0.5 font-mono font-black text-[#21162f]">
                                                    i &lt;= numbers.length
                                                </code>{" "}
                                                membuat perulangan mengakses
                                                indeks ke-4, sedangkan indeks
                                                terakhir array adalah 3. Gunakan{" "}
                                                <code className="rounded bg-white px-1.5 py-0.5 font-mono font-black text-[#21162f]">
                                                    i &lt; numbers.length
                                                </code>
                                                .
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            )}

                            {previewMode === "hint" && (
                                <div
                                    id="preview-feedback"
                                    aria-live="polite"
                                    className="mt-5 rounded-2xl border-2 border-[#21162f] bg-[#fff2b8] p-4 shadow-[4px_4px_0_#21162f]"
                                >
                                    <div className="flex items-start gap-3">
                                        <span className="mt-0.5 grid h-9 w-9 shrink-0 place-items-center rounded-xl border-2 border-[#21162f] bg-white">
                                            <Lightbulb
                                                className="h-5 w-5 text-[#b57000]"
                                                strokeWidth={2.8}
                                            />
                                        </span>

                                        <div>
                                            <p className="font-black text-[#21162f]">
                                                Petunjuk
                                            </p>

                                            <p className="mt-1 text-sm font-semibold leading-6 text-[#6f6031]">
                                                Panjang array adalah 4, tetapi
                                                indeksnya dimulai dari 0.
                                                Perulangan harus berhenti
                                                sebelum nilai indeks sama dengan
                                                panjang array.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            )}

                            <div className="mt-5 grid gap-3 sm:grid-cols-2">
                                <button
                                    type="button"
                                    onClick={handleCheckAnswer}
                                    aria-expanded={previewMode === "answer"}
                                    aria-controls="preview-feedback"
                                    className={`nb-button w-full ${
                                        previewMode === "answer"
                                            ? "bg-[#d4f8e5]"
                                            : "nb-button-primary"
                                    }`}
                                >
                                    {previewMode === "answer" ? (
                                        <>
                                            <RotateCcw
                                                className="h-4 w-4"
                                                strokeWidth={2.8}
                                            />
                                            Ulangi Preview
                                        </>
                                    ) : (
                                        <>
                                            Periksa Jawaban
                                            <ArrowRight
                                                className="h-4 w-4"
                                                strokeWidth={2.8}
                                            />
                                        </>
                                    )}
                                </button>

                                <button
                                    type="button"
                                    onClick={handleToggleHint}
                                    aria-expanded={previewMode === "hint"}
                                    aria-controls="preview-feedback"
                                    className={`nb-button w-full ${
                                        previewMode === "hint"
                                            ? "bg-[#fff2b8]"
                                            : "nb-button-secondary"
                                    }`}
                                >
                                    <Lightbulb
                                        className="h-4 w-4"
                                        strokeWidth={2.8}
                                    />
                                    {previewMode === "hint"
                                        ? "Tutup Petunjuk"
                                        : "Lihat Petunjuk"}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section className="px-4 py-10 sm:px-6 lg:px-8">
                <div className="mx-auto grid max-w-7xl gap-6 md:grid-cols-3">
                    <StatCard
                        label="Tantangan tersedia"
                        value={stats.challenges}
                        description="Beragam kasus JavaScript, PHP, dan SQL."
                        background="bg-[#fff8d9]"
                        icon={Target}
                    />

                    <StatCard
                        label="Bug hunters"
                        value={stats.hunters}
                        description="Pengguna yang terus melatih kemampuan debugging."
                        background="bg-[#e8f6ff]"
                        icon={Users}
                    />

                    <StatCard
                        label="Tantangan dituntaskan"
                        value={stats.completedSubmissions}
                        description="Submission yang berhasil diselesaikan pengguna."
                        background="bg-[#e7f9ef]"
                        icon={Trophy}
                    />
                </div>
            </section>

            <section className="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
                <div className="mx-auto max-w-3xl text-center">
                    <div className="inline-flex items-center gap-2 rounded-full border border-[#21162f]/10 bg-white px-4 py-2 text-sm font-black text-[#7b5aaa] shadow-sm">
                        <Sparkles className="h-4 w-4" strokeWidth={2.7} />
                        Alur pembelajaran
                    </div>

                    <h2 className="section-title mt-5 text-balance text-[#21162f]">
                        Bukan sekadar menebak jawaban.
                    </h2>

                    <p className="mx-auto mt-5 max-w-2xl font-semibold leading-8 text-[#665f73]">
                        Setiap tantangan dirancang untuk memaksa Anda memahami
                        lokasi bug, solusi yang benar, dan alasan teknis di
                        balik perbaikannya.
                    </p>
                </div>

                <div className="mt-12 grid gap-7 md:grid-cols-3">
                    {features.map((feature) => {
                        const Icon = feature.icon;

                        return (
                            <article
                                key={feature.number}
                                className={`nb-card group relative overflow-hidden p-7 ${feature.background}`}
                            >
                                <span className="absolute right-5 top-5 text-5xl font-black tracking-[-0.08em] text-[#21162f]/10">
                                    {feature.number}
                                </span>

                                <span
                                    className={`neo-icon-box h-14 w-14 ${feature.iconBackground}`}
                                >
                                    <Icon
                                        className="h-7 w-7"
                                        strokeWidth={2.7}
                                    />
                                </span>

                                <h3 className="mt-7 text-2xl font-black tracking-[-0.035em] text-[#21162f]">
                                    {feature.title}
                                </h3>

                                <p className="mt-4 font-semibold leading-7 text-[#665f73]">
                                    {feature.description}
                                </p>
                            </article>
                        );
                    })}
                </div>
            </section>

            <section className="border-y border-[#21162f]/10 bg-white/55 py-20 backdrop-blur-sm">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col justify-between gap-7 md:flex-row md:items-end">
                        <div className="max-w-3xl">
                            <div className="inline-flex items-center gap-2 rounded-full border border-[#21162f]/10 bg-[#f8f6fc] px-4 py-2 text-sm font-black text-[#7b5aaa]">
                                <Bug className="h-4 w-4" strokeWidth={2.7} />
                                Tantangan terbaru
                            </div>

                            <h2 className="section-title mt-5 text-[#21162f]">
                                Pilih bug pertama Anda.
                            </h2>

                            <p className="mt-4 max-w-2xl font-semibold leading-7 text-[#665f73]">
                                Mulai dari tantangan yang sesuai kemampuan,
                                analisis kode, dan tingkatkan skor Anda.
                            </p>
                        </div>

                        <Link
                            href={route("challenges.index")}
                            className="nb-button nb-button-light self-start px-5"
                        >
                            Lihat Semua
                            <ArrowRight className="h-4 w-4" strokeWidth={2.8} />
                        </Link>
                    </div>

                    {featuredChallenges.length > 0 ? (
                        <div className="mt-12 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                            {featuredChallenges.map((challenge) => (
                                <ChallengeCard
                                    key={challenge.id}
                                    challenge={challenge}
                                />
                            ))}
                        </div>
                    ) : (
                        <div className="nb-card mt-12 bg-white p-10 text-center">
                            <span className="neo-icon-box mx-auto h-16 w-16 bg-[#ffe8f2]">
                                <Bug className="h-8 w-8" strokeWidth={2.6} />
                            </span>

                            <p className="mt-6 text-2xl font-black text-[#21162f]">
                                Tantangan belum tersedia.
                            </p>

                            <p className="mt-2 font-semibold text-[#665f73]">
                                Tantangan baru akan muncul di bagian ini.
                            </p>
                        </div>
                    )}
                </div>
            </section>

            <section className="mx-auto max-w-6xl px-4 py-24 sm:px-6 lg:px-8">
                <div className="nb-card relative overflow-hidden bg-gradient-to-br from-[#fff3be] via-[#ffd8c3] to-[#ffd7eb] p-8 text-center sm:p-14">
                    <div
                        aria-hidden="true"
                        className="absolute -left-14 -top-14 h-48 w-48 rounded-full border-2 border-[#21162f]/10 bg-white/25"
                    />

                    <div
                        aria-hidden="true"
                        className="absolute -bottom-20 -right-14 h-56 w-56 rounded-full border-2 border-[#21162f]/10 bg-[#9c88f7]/15"
                    />

                    <div className="relative">
                        <span className="neo-icon-box mx-auto h-16 w-16 bg-white">
                            <Sparkles className="h-8 w-8" strokeWidth={2.7} />
                        </span>

                        <p className="mt-7 text-sm font-black uppercase tracking-[0.16em] text-[#7b5aaa]">
                            Siap menguji logika?
                        </p>

                        <h2 className="mx-auto mt-4 max-w-4xl text-balance text-4xl font-black tracking-[-0.06em] text-[#21162f] sm:text-6xl">
                            Jadikan setiap kesalahan sebagai bahan latihan.
                        </h2>

                        <p className="mx-auto mt-6 max-w-2xl text-base font-semibold leading-8 text-[#665f73] sm:text-lg">
                            Buat akun, selesaikan tantangan, kumpulkan poin, dan
                            tingkatkan posisi Anda pada leaderboard.
                        </p>

                        <Link
                            href={route("register")}
                            className="nb-button nb-button-primary mt-9 px-7 py-4 text-base"
                        >
                            Daftar Sekarang
                            <ArrowRight className="h-5 w-5" strokeWidth={2.8} />
                        </Link>
                    </div>
                </div>
            </section>
        </PublicLayout>
    );
}
