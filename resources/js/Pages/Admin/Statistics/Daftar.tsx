import StatCard from "@/Components/KartuStatistik";
import AdminLayout from "@/Layouts/TataLetakAdmin";
import { Head, Link } from "@inertiajs/react";
import {
    Bar,
    BarChart,
    CartesianGrid,
    Cell,
    Line,
    LineChart,
    Pie,
    PieChart,
    ResponsiveContainer,
    Tooltip,
    XAxis,
    YAxis,
} from "recharts";

type Summary = {
    users: number;
    challenges: number;
    submissions: number;
    completedSubmissions: number;
    completionRate: number;
    averageScore: number;
};

type StatusItem = {
    name: string;
    value: number;
};

type DailySubmission = {
    date: string;
    label: string;
    total: number;
    completed: number;
};

type PerformanceItem = {
    name: string;
    submissions: number;
    average_score: number;
};

type DifficultyPerformance = PerformanceItem & {
    base_points: number;
};

type TopChallenge = {
    id: number;
    title: string;
    slug: string;
    category: string;
    difficulty: string;
    submissions_count: number;
    completed_count: number;
    completion_rate: number;
};

type TopUser = {
    id: number;
    name: string;
    email: string;
    total_points: number;
    submissions_count: number;
    completed_challenges: number;
};

type StatisticsProps = {
    summary: Summary;
    statusChart: StatusItem[];
    dailySubmissions: DailySubmission[];
    categoryPerformance: PerformanceItem[];
    difficultyPerformance: DifficultyPerformance[];
    topChallenges: TopChallenge[];
    topUsers: TopUser[];
};

const pieColors = ["#ff6b6b", "#ffbd70", "#9ef0b8"];

export default function StatisticsIndex({
    summary,
    statusChart,
    dailySubmissions,
    categoryPerformance,
    difficultyPerformance,
    topChallenges,
    topUsers,
}: StatisticsProps) {
    return (
        <AdminLayout
            title="Statistik"
            description="Analisis aktivitas pengguna, submission, tingkat penyelesaian, kategori, dan tantangan."
        >
            <Head title="Statistik Admin" />

            <section className="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                <StatCard
                    label="Pengguna"
                    value={summary.users}
                    description="Akun dengan role user"
                    background="bg-[#9ed8ff]"
                />

                <StatCard
                    label="Tantangan"
                    value={summary.challenges}
                    description="Tantangan aktif dan tersedia"
                    background="bg-[#ffd93d]"
                />

                <StatCard
                    label="Submission"
                    value={summary.submissions}
                    description="Semua jawaban pengguna"
                    background="bg-[#b7a4ff]"
                />

                <StatCard
                    label="Submission Selesai"
                    value={summary.completedSubmissions}
                    description="Jawaban berstatus completed"
                    background="bg-[#9ef0b8]"
                />

                <StatCard
                    label="Tingkat Penyelesaian"
                    value={`${summary.completionRate}%`}
                    description="Persentase submission selesai"
                    background="bg-[#ffbd70]"
                />

                <StatCard
                    label="Rata-rata Skor"
                    value={summary.averageScore}
                    description="Rata-rata skor seluruh submission"
                    background="bg-[#ff9c9c]"
                />
            </section>

            <section className="mt-8 grid gap-8 xl:grid-cols-[1.35fr_0.65fr]">
                <article className="nb-card bg-white p-5">
                    <h2 className="text-2xl font-black">
                        Aktivitas 14 Hari Terakhir
                    </h2>

                    <p className="mt-2 font-semibold text-neutral-700">
                        Perbandingan semua submission dan submission yang
                        berhasil diselesaikan.
                    </p>

                    <div className="mt-6 h-[350px]">
                        <ResponsiveContainer width="100%" height="100%">
                            <LineChart
                                data={dailySubmissions}
                                margin={{
                                    top: 10,
                                    right: 20,
                                    bottom: 20,
                                    left: 0,
                                }}
                            >
                                <CartesianGrid
                                    strokeDasharray="5 5"
                                    stroke="#111111"
                                />

                                <XAxis
                                    dataKey="label"
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

                                <Line
                                    type="monotone"
                                    dataKey="total"
                                    name="Semua submission"
                                    stroke="#6d5dfc"
                                    strokeWidth={4}
                                    dot={{
                                        r: 4,
                                        strokeWidth: 2,
                                        stroke: "#111111",
                                    }}
                                />

                                <Line
                                    type="monotone"
                                    dataKey="completed"
                                    name="Selesai"
                                    stroke="#18a558"
                                    strokeWidth={4}
                                    dot={{
                                        r: 4,
                                        strokeWidth: 2,
                                        stroke: "#111111",
                                    }}
                                />
                            </LineChart>
                        </ResponsiveContainer>
                    </div>
                </article>

                <article className="nb-card bg-[#fff1a8] p-5">
                    <h2 className="text-2xl font-black">Status Submission</h2>

                    <div className="mt-6 h-[350px]">
                        <ResponsiveContainer width="100%" height="100%">
                            <PieChart>
                                <Pie
                                    data={statusChart}
                                    dataKey="value"
                                    nameKey="name"
                                    cx="50%"
                                    cy="50%"
                                    outerRadius={115}
                                    label
                                    stroke="#111111"
                                    strokeWidth={3}
                                >
                                    {statusChart.map((item, index) => (
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
                    </div>
                </article>
            </section>

            <section className="mt-8 grid gap-8 xl:grid-cols-2">
                <article className="nb-card bg-[#9ed8ff] p-5">
                    <h2 className="text-2xl font-black">Performa Kategori</h2>

                    <p className="mt-2 font-semibold">
                        Jumlah submission berdasarkan kategori.
                    </p>

                    <div className="mt-6 h-[340px]">
                        <ResponsiveContainer width="100%" height="100%">
                            <BarChart
                                data={categoryPerformance}
                                margin={{
                                    top: 10,
                                    right: 20,
                                    bottom: 20,
                                    left: 0,
                                }}
                            >
                                <CartesianGrid
                                    strokeDasharray="5 5"
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

                                <YAxis allowDecimals={false} stroke="#111111" />

                                <Tooltip
                                    contentStyle={{
                                        border: "3px solid #111111",
                                        boxShadow: "4px 4px 0 #111111",
                                        fontWeight: 700,
                                    }}
                                />

                                <Bar
                                    dataKey="submissions"
                                    name="Submission"
                                    fill="#ffd93d"
                                    stroke="#111111"
                                    strokeWidth={3}
                                />
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                </article>

                <article className="nb-card bg-[#b7a4ff] p-5">
                    <h2 className="text-2xl font-black">
                        Rata-rata Skor Kesulitan
                    </h2>

                    <p className="mt-2 font-semibold">
                        Perbandingan rata-rata skor berdasarkan tingkat
                        kesulitan.
                    </p>

                    <div className="mt-6 h-[340px]">
                        <ResponsiveContainer width="100%" height="100%">
                            <BarChart
                                data={difficultyPerformance}
                                margin={{
                                    top: 10,
                                    right: 20,
                                    bottom: 20,
                                    left: 0,
                                }}
                            >
                                <CartesianGrid
                                    strokeDasharray="5 5"
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

                                <YAxis allowDecimals={false} stroke="#111111" />

                                <Tooltip
                                    contentStyle={{
                                        border: "3px solid #111111",
                                        boxShadow: "4px 4px 0 #111111",
                                        fontWeight: 700,
                                    }}
                                />

                                <Bar
                                    dataKey="average_score"
                                    name="Rata-rata skor"
                                    fill="#9ef0b8"
                                    stroke="#111111"
                                    strokeWidth={3}
                                />
                            </BarChart>
                        </ResponsiveContainer>
                    </div>
                </article>
            </section>

            <section className="mt-8">
                <h2 className="text-2xl font-black">
                    Tantangan Paling Banyak Dikerjakan
                </h2>

                <div className="mt-5 overflow-x-auto border-[3px] border-black bg-white shadow-[6px_6px_0_#111]">
                    <table className="nb-table min-w-[900px]">
                        <thead>
                            <tr>
                                <th>Tantangan</th>
                                <th>Kategori</th>
                                <th>Kesulitan</th>
                                <th>Submission</th>
                                <th>Selesai</th>
                                <th>Rasio Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            {topChallenges.map((challenge) => (
                                <tr key={challenge.id}>
                                    <td className="font-black">
                                        {challenge.title}
                                    </td>

                                    <td>
                                        <span className="nb-badge bg-[#9ed8ff]">
                                            {challenge.category}
                                        </span>
                                    </td>

                                    <td className="font-bold">
                                        {challenge.difficulty}
                                    </td>

                                    <td className="font-black">
                                        {challenge.submissions_count}
                                    </td>

                                    <td className="font-black">
                                        {challenge.completed_count}
                                    </td>

                                    <td className="font-black">
                                        {challenge.completion_rate}%
                                    </td>

                                    <td>
                                        <Link
                                            href={route(
                                                "admin.challenges.edit",
                                                challenge.slug,
                                            )}
                                            className="nb-button bg-[#ffd93d] text-xs"
                                        >
                                            Buka
                                        </Link>
                                    </td>
                                </tr>
                            ))}

                            {topChallenges.length === 0 && (
                                <tr>
                                    <td
                                        colSpan={7}
                                        className="text-center font-black"
                                    >
                                        Belum ada aktivitas tantangan.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </section>

            <section className="mt-8">
                <h2 className="text-2xl font-black">
                    Pengguna dengan Poin Tertinggi
                </h2>

                <div className="mt-5 overflow-x-auto border-[3px] border-black bg-white shadow-[6px_6px_0_#111]">
                    <table className="nb-table min-w-[800px]">
                        <thead>
                            <tr>
                                <th>Peringkat</th>
                                <th>Pengguna</th>
                                <th>Poin</th>
                                <th>Tantangan Selesai</th>
                                <th>Submission</th>
                            </tr>
                        </thead>

                        <tbody>
                            {topUsers.map((user, index) => (
                                <tr key={user.id}>
                                    <td>
                                        <span className="grid h-10 w-10 place-items-center border-2 border-black bg-[#ffd93d] font-black shadow-[2px_2px_0_#111]">
                                            {index + 1}
                                        </span>
                                    </td>

                                    <td>
                                        <p className="font-black">
                                            {user.name}
                                        </p>

                                        <p className="mt-1 text-xs font-bold text-neutral-600">
                                            {user.email}
                                        </p>
                                    </td>

                                    <td className="text-lg font-black">
                                        {user.total_points}
                                    </td>

                                    <td className="font-black">
                                        {user.completed_challenges}
                                    </td>

                                    <td className="font-black">
                                        {user.submissions_count}
                                    </td>
                                </tr>
                            ))}

                            {topUsers.length === 0 && (
                                <tr>
                                    <td
                                        colSpan={5}
                                        className="text-center font-black"
                                    >
                                        Belum ada data pengguna.
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
