import StatCard from "@/Components/KartuStatistik";
import StatusBadge from "@/Components/LencanaStatus";
import AdminLayout from "@/Layouts/TataLetakAdmin";
import { SubmissionStatus } from "@/types";
import { Head, Link } from "@inertiajs/react";
import {
    Bar,
    BarChart,
    CartesianGrid,
    Cell,
    Pie,
    PieChart,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    YAxis,
} from "recharts";

type ChartItem = {
    name: string;
    value: number;
};

type RecentSubmission = {
    id: number;
    final_score: number;
    status: SubmissionStatus;
    created_at: string;
    user: {
        id: number;
        name: string;
        email: string;
    };
    challenge: {
        id: number;
        title: string;
        slug: string;
    };
};

type AdminDashboardProps = {
    summary: {
        users: number;
        admins: number;
        challenges: number;
        publishedChallenges: number;
        submissions: number;
        completedSubmissions: number;
    };
    statusChart: ChartItem[];
    categoryChart: ChartItem[];
    recentSubmissions: RecentSubmission[];
};

const pieColors = ["#ff6b6b", "#ffd93d", "#9ef0b8", "#9ed8ff", "#b7a4ff"];

export default function AdminDashboard({
    summary,
    statusChart,
    categoryChart,
    recentSubmissions,
}: AdminDashboardProps) {
    return (
        <AdminLayout
            title="Dashboard Administrator"
            description="Pantau pengguna, tantangan, submission, dan distribusi konten BugHunt."
        >
            <Head title="Dashboard Admin" />

            <section className="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                <StatCard
                    label="Pengguna"
                    value={summary.users}
                    description={`${summary.admins} akun administrator`}
                    background="bg-[#9ed8ff]"
                />

                <StatCard
                    label="Semua Tantangan"
                    value={summary.challenges}
                    description={`${summary.publishedChallenges} tantangan diterbitkan`}
                    background="bg-[#ffd93d]"
                />

                <StatCard
                    label="Submission"
                    value={summary.submissions}
                    description={`${summary.completedSubmissions} submission selesai`}
                    background="bg-[#9ef0b8]"
                />
            </section>

            <section className="mt-8 grid gap-8 xl:grid-cols-2">
                <article className="nb-card bg-white p-5">
                    <h2 className="text-xl font-black">
                        Submission Berdasarkan Status
                    </h2>

                    <div className="mt-6 h-[320px]">
                        {statusChart.length > 0 ? (
                            <ResponsiveContainer width="100%" height="100%">
                                <BarChart
                                    data={statusChart}
                                    margin={{
                                        top: 10,
                                        right: 10,
                                        left: 0,
                                        bottom: 20,
                                    }}
                                >
                                    <CartesianGrid
                                        strokeDasharray="4 4"
                                        stroke="#111111"
                                    />

                                    <XAxis
                                        dataKey="name"
                                        stroke="#111111"
                                        tick={{
                                            fill: "#111111",
                                            fontWeight: 700,
                                        }}
                                    />

                                    <YAxis
                                        allowDecimals={false}
                                        stroke="#111111"
                                        tick={{
                                            fill: "#111111",
                                            fontWeight: 700,
                                        }}
                                    />

                                    <Tooltip
                                        contentStyle={{
                                            border: "3px solid #111111",
                                            boxShadow: "4px 4px 0 #111111",
                                            fontWeight: 700,
                                        }}
                                    />

                                    <Bar
                                        dataKey="value"
                                        fill="#ff6b6b"
                                        stroke="#111111"
                                        strokeWidth={2}
                                    />
                                </BarChart>
                            </ResponsiveContainer>
                        ) : (
                            <div className="grid h-full place-items-center border-[3px] border-black bg-[#fff1a8] font-black">
                                Belum ada submission
                            </div>
                        )}
                    </div>
                </article>

                <article className="nb-card bg-[#fff1a8] p-5">
                    <h2 className="text-xl font-black">
                        Tantangan per Kategori
                    </h2>

                    <div className="mt-6 h-[320px]">
                        {categoryChart.length > 0 ? (
                            <ResponsiveContainer width="100%" height="100%">
                                <PieChart>
                                    <Pie
                                        data={categoryChart}
                                        dataKey="value"
                                        nameKey="name"
                                        cx="50%"
                                        cy="50%"
                                        outerRadius={110}
                                        label
                                        stroke="#111111"
                                        strokeWidth={3}
                                    >
                                        {categoryChart.map((item, index) => (
                                            <Cell
                                                key={`${item.name}-${index}`}
                                                fill={
                                                    pieColors[
                                                        index % pieColors.length
                                                    ]
                                                }
                                            />
                                        ))}
                                    </Pie>

                                    <Tooltip
                                        contentStyle={{
                                            border: "3px solid #111111",
                                            boxShadow: "4px 4px 0 #111111",
                                            fontWeight: 700,
                                        }}
                                    />
                                </PieChart>
                            </ResponsiveContainer>
                        ) : (
                            <div className="grid h-full place-items-center border-[3px] border-black bg-white font-black">
                                Belum ada kategori
                            </div>
                        )}
                    </div>
                </article>
            </section>

            <section className="mt-8">
                <div className="mb-5 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p className="text-xs font-black uppercase tracking-[0.16em]">
                            Aktivitas Terbaru
                        </p>

                        <h2 className="mt-2 text-2xl font-black">
                            Submission Pengguna
                        </h2>
                    </div>

                    <Link
                        href={route("admin.submissions.index")}
                        className="nb-button bg-[#ffd93d] text-sm"
                    >
                        Lihat Semua
                    </Link>
                </div>

                <div className="overflow-x-auto border-[3px] border-black bg-white shadow-[6px_6px_0_#111]">
                    <table className="nb-table min-w-[800px]">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Tantangan</th>
                                <th>Status</th>
                                <th>Skor</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            {recentSubmissions.length > 0 ? (
                                recentSubmissions.map((submission) => (
                                    <tr key={submission.id}>
                                        <td>
                                            <strong>
                                                {submission.user.name}
                                            </strong>

                                            <p className="mt-1 text-xs font-bold text-neutral-600">
                                                {submission.user.email}
                                            </p>
                                        </td>

                                        <td className="font-bold">
                                            {submission.challenge.title}
                                        </td>

                                        <td>
                                            <StatusBadge
                                                status={submission.status}
                                            />
                                        </td>

                                        <td className="font-black">
                                            {submission.final_score}
                                        </td>

                                        <td>
                                            {new Date(
                                                submission.created_at,
                                            ).toLocaleString("id-ID", {
                                                dateStyle: "medium",
                                                timeStyle: "short",
                                            })}
                                        </td>

                                        <td>
                                            <Link
                                                href={route(
                                                    "admin.submissions.show",
                                                    submission.id,
                                                )}
                                                className="nb-button bg-[#9ed8ff] text-xs"
                                            >
                                                Detail
                                            </Link>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td
                                        colSpan={6}
                                        className="text-center font-bold"
                                    >
                                        Belum ada submission.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </section>
        </AdminLayout>
    );
}
