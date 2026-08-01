import PublicLayout from "@/Layouts/PublicLayout";
import { PageProps } from "@/types";
import { Head, Link, usePage } from "@inertiajs/react";

type Leader = {
    rank: number;
    id: number;
    name: string;
    total_points: number;
    completed_challenges: number;
    joined_at: string;
};

type LeaderboardProps = {
    leaders: Leader[];
};

const podiumBackground: Record<number, string> = {
    1: "bg-[#ffd93d]",
    2: "bg-[#d6d6d6]",
    3: "bg-[#ffbd70]",
};

const headerCellClass =
    "border-b border-r border-black/20 bg-gradient-to-r from-[#ffd93d]/90 to-[#ff9b67]/75 px-4 py-4 text-left text-xs font-black uppercase tracking-[0.06em] align-middle";

const bodyCellClass = "border-r border-black/20 px-4 py-4 align-middle";

function formatJoinedDate(value: string): string {
    return new Date(value).toLocaleDateString("id-ID", {
        dateStyle: "medium",
    });
}

export default function Leaderboard({ leaders }: LeaderboardProps) {
    const { auth } = usePage<PageProps>().props;
    const topThree = leaders.slice(0, 3);

    return (
        <PublicLayout>
            <Head title="Peringkat" />

            <section className="border-b-[3px] border-black bg-[#b7a4ff]">
                <div className="mx-auto max-w-7xl px-4 py-12 text-center sm:px-6 sm:py-16 lg:px-8">
                    <p className="text-xs font-black uppercase tracking-[0.15em] sm:text-sm sm:tracking-[0.18em]">
                        Peringkat Pengguna
                    </p>

                    <h1 className="page-title mt-4">
                        Lihat siapa yang mengumpulkan poin terbanyak.
                    </h1>

                    <p className="mx-auto mt-6 max-w-2xl text-base font-semibold leading-7 sm:text-lg sm:leading-8">
                        Peringkat dihitung dari skor terbaik setiap pengguna
                        pada masing-masing tantangan.
                    </p>
                </div>
            </section>

            <div className="mx-auto max-w-6xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
                {topThree.length > 0 && (
                    <section className="grid gap-5 md:grid-cols-3 md:items-end md:gap-6">
                        {topThree.map((leader) => (
                            <article
                                key={leader.id}
                                className={`nb-card p-5 text-center sm:p-6 ${
                                    podiumBackground[leader.rank] ?? "bg-white"
                                } ${
                                    leader.rank === 1
                                        ? "md:order-2 md:min-h-[330px]"
                                        : leader.rank === 2
                                          ? "md:order-1 md:min-h-[280px]"
                                          : "md:order-3 md:min-h-[250px]"
                                }`}
                            >
                                <div className="mx-auto grid h-14 w-14 place-items-center rounded-full border-[3px] border-black bg-white text-xl font-black shadow-[3px_3px_0_#111] sm:h-16 sm:w-16 sm:text-2xl sm:shadow-[4px_4px_0_#111]">
                                    #{leader.rank}
                                </div>

                                <h2 className="mt-5 break-words text-xl font-black sm:mt-6 sm:text-2xl">
                                    {leader.name}
                                </h2>

                                <p className="mt-4 text-4xl font-black tracking-[-0.06em] sm:mt-5 sm:text-5xl">
                                    {leader.total_points}
                                </p>

                                <p className="mt-1 text-sm font-black uppercase tracking-wide">
                                    poin
                                </p>

                                <div className="mt-5 border-[3px] border-black bg-white p-3 font-bold sm:mt-6">
                                    {leader.completed_challenges} tantangan
                                    selesai
                                </div>
                            </article>
                        ))}
                    </section>
                )}

                {leaders.length > 0 ? (
                    <section className="mt-10 sm:mt-12">
                        <div className="grid gap-4 md:hidden">
                            {leaders.map((leader) => {
                                const currentUser = auth.user?.id === leader.id;

                                return (
                                    <article
                                        key={leader.id}
                                        className={`nb-card min-w-0 p-4 ${
                                            currentUser
                                                ? "bg-[#fff1a8]"
                                                : "bg-white"
                                        }`}
                                    >
                                        <div className="flex min-w-0 items-start justify-between gap-3">
                                            <div className="flex min-w-0 items-center gap-3">
                                                <span className="grid h-11 w-11 shrink-0 place-items-center border-2 border-black bg-[#ffd93d] font-black shadow-[2px_2px_0_#111]">
                                                    {leader.rank}
                                                </span>

                                                <div className="min-w-0">
                                                    <h2 className="break-words font-black leading-tight">
                                                        {leader.name}
                                                    </h2>

                                                    {currentUser && (
                                                        <span className="mt-2 inline-flex border-2 border-black bg-[#9ef0b8] px-2 py-1 text-[0.65rem] font-black">
                                                            POSISIMU
                                                        </span>
                                                    )}
                                                </div>
                                            </div>

                                            <div className="shrink-0 text-right">
                                                <strong className="block text-2xl font-black leading-none">
                                                    {leader.total_points}
                                                </strong>

                                                <span className="mt-1 block text-[0.65rem] font-black uppercase tracking-wide">
                                                    poin
                                                </span>
                                            </div>
                                        </div>

                                        <div className="mt-4 grid grid-cols-1 gap-3 border-t-2 border-black/15 pt-4 min-[380px]:grid-cols-2">
                                            <div className="rounded-xl bg-[#f2eff8] p-3">
                                                <p className="text-[0.65rem] font-black uppercase tracking-wide text-neutral-600">
                                                    Tantangan Selesai
                                                </p>

                                                <p className="mt-1 font-black">
                                                    {
                                                        leader.completed_challenges
                                                    }
                                                </p>
                                            </div>

                                            <div className="rounded-xl bg-[#f2eff8] p-3">
                                                <p className="text-[0.65rem] font-black uppercase tracking-wide text-neutral-600">
                                                    Bergabung
                                                </p>

                                                <p className="mt-1 font-black">
                                                    {formatJoinedDate(
                                                        leader.joined_at,
                                                    )}
                                                </p>
                                            </div>
                                        </div>
                                    </article>
                                );
                            })}
                        </div>

                        <div className="hidden overflow-x-auto pb-3 pr-3 md:block">
                            <div className="min-w-[700px] overflow-hidden rounded-2xl border-[3px] border-black bg-white shadow-[7px_7px_0_#111]">
                                <table
                                    className="w-full border-separate border-spacing-0"
                                    aria-label="Peringkat pengguna"
                                >
                                    <thead>
                                        <tr>
                                            <th
                                                scope="col"
                                                className={`${headerCellClass} w-[15%]`}
                                            >
                                                Peringkat
                                            </th>

                                            <th
                                                scope="col"
                                                className={`${headerCellClass} w-[24%]`}
                                            >
                                                Nama
                                            </th>

                                            <th
                                                scope="col"
                                                className={`${headerCellClass} w-[26%]`}
                                            >
                                                Tantangan Selesai
                                            </th>

                                            <th
                                                scope="col"
                                                className={`${headerCellClass} w-[17%]`}
                                            >
                                                Total Poin
                                            </th>

                                            <th
                                                scope="col"
                                                className={`${headerCellClass} w-[18%] border-r-0`}
                                            >
                                                Bergabung
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        {leaders.map((leader, index) => {
                                            const currentUser =
                                                auth.user?.id === leader.id;

                                            const isLastRow =
                                                index === leaders.length - 1;

                                            const bottomBorderClass = isLastRow
                                                ? ""
                                                : "border-b border-black/20";

                                            return (
                                                <tr
                                                    key={leader.id}
                                                    className={
                                                        currentUser
                                                            ? "bg-[#fff1a8]"
                                                            : "bg-white"
                                                    }
                                                >
                                                    <td
                                                        className={`${bodyCellClass} ${bottomBorderClass}`}
                                                    >
                                                        <span className="grid h-10 w-10 place-items-center border-2 border-black bg-[#ffd93d] font-black shadow-[2px_2px_0_#111]">
                                                            {leader.rank}
                                                        </span>
                                                    </td>

                                                    <td
                                                        className={`${bodyCellClass} ${bottomBorderClass}`}
                                                    >
                                                        <p className="font-black">
                                                            {leader.name}
                                                        </p>

                                                        {currentUser && (
                                                            <span className="mt-2 inline-flex border-2 border-black bg-[#9ef0b8] px-2 py-1 text-xs font-black">
                                                                POSISIMU
                                                            </span>
                                                        )}
                                                    </td>

                                                    <td
                                                        className={`${bodyCellClass} ${bottomBorderClass}`}
                                                    >
                                                        <strong>
                                                            {
                                                                leader.completed_challenges
                                                            }
                                                        </strong>
                                                    </td>

                                                    <td
                                                        className={`${bodyCellClass} ${bottomBorderClass}`}
                                                    >
                                                        <strong className="text-lg">
                                                            {
                                                                leader.total_points
                                                            }
                                                        </strong>
                                                    </td>

                                                    <td
                                                        className={`${bodyCellClass} ${bottomBorderClass} whitespace-nowrap border-r-0`}
                                                    >
                                                        {formatJoinedDate(
                                                            leader.joined_at,
                                                        )}
                                                    </td>
                                                </tr>
                                            );
                                        })}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                ) : (
                    <div className="nb-card bg-[#fff1a8] p-6 text-center sm:p-10">
                        <h2 className="text-2xl font-black">
                            Belum ada peringkat.
                        </h2>

                        <p className="mt-4 font-semibold">
                            Peringkat akan muncul setelah ada pengguna yang
                            menyelesaikan tantangan.
                        </p>
                    </div>
                )}

                {!auth.user && (
                    <section className="nb-card mt-10 bg-[#9ef0b8] p-6 text-center sm:mt-12 sm:p-8">
                        <h2 className="text-2xl font-black tracking-[-0.04em] sm:text-3xl">
                            Mau ikut masuk peringkat?
                        </h2>

                        <p className="mt-4 font-semibold leading-7">
                            Buat akun, kerjakan tantangan, dan kumpulkan poin
                            dari jawaban terbaikmu.
                        </p>

                        <Link
                            href={route("register")}
                            className="nb-button mt-6 w-full bg-[#ffd93d] sm:w-auto"
                        >
                            Buat Akun
                        </Link>
                    </section>
                )}
            </div>
        </PublicLayout>
    );
}
