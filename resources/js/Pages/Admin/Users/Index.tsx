import Pagination from "@/Components/Pagination";
import AdminLayout from "@/Layouts/AdminLayout";
import { PageProps, Paginator, User } from "@/types";
import { Head, router, usePage } from "@inertiajs/react";
import { FormEvent, useState } from "react";

type UserRow = User & {
    submissions_count: number;
    completed_challenges: number;
    created_at: string;
};

type UserIndexProps = {
    users: Paginator<UserRow>;
    filters: {
        search: string;
        role: string;
    };
};

export default function UserIndex({ users, filters }: UserIndexProps) {
    const currentUser = usePage<PageProps>().props.auth.user;

    const [search, setSearch] = useState(filters.search);

    const [role, setRole] = useState(filters.role);

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        router.get(
            route("admin.users.index"),
            {
                search: search || undefined,
                role: role || undefined,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    };

    const updateRole = (user: UserRow, nextRole: "user" | "admin") => {
        if (!window.confirm(`Ubah role ${user.name} menjadi ${nextRole}?`)) {
            return;
        }

        router.put(
            route("admin.users.update", user.id),
            {
                role: nextRole,
            },
            {
                preserveScroll: true,
            },
        );
    };

    return (
        <AdminLayout
            title="Kelola Pengguna"
            description="Tinjau akun, aktivitas, poin, dan role pengguna BugHunt."
        >
            <Head title="Kelola Pengguna" />

            <form
                onSubmit={submit}
                className="nb-card grid gap-4 bg-[#9ed8ff] p-5 md:grid-cols-[minmax(0,1fr)_200px_auto]"
            >
                <input
                    value={search}
                    onChange={(event) => setSearch(event.target.value)}
                    className="nb-input"
                    placeholder="Cari nama atau email"
                />

                <select
                    value={role}
                    onChange={(event) => setRole(event.target.value)}
                    className="nb-input"
                >
                    <option value="">Semua role</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>

                <button className="nb-button bg-[#ffd93d]">Filter</button>
            </form>

            <div className="mt-7 overflow-x-auto border-[3px] border-black bg-white shadow-[6px_6px_0_#111]">
                <table className="nb-table min-w-[1000px]">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Poin</th>
                            <th>Selesai</th>
                            <th>Submission</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        {users.data.map((user) => (
                            <tr key={user.id}>
                                <td>
                                    <strong>{user.name}</strong>

                                    <p className="mt-1 text-xs font-bold text-neutral-600">
                                        {user.email}
                                    </p>

                                    {currentUser?.id === user.id && (
                                        <span className="nb-badge mt-2 bg-[#ffd93d]">
                                            Akun Anda
                                        </span>
                                    )}
                                </td>

                                <td>
                                    <span
                                        className={`nb-badge ${
                                            user.role === "admin"
                                                ? "bg-[#b7a4ff]"
                                                : "bg-[#9ed8ff]"
                                        }`}
                                    >
                                        {user.role}
                                    </span>
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

                                <td>
                                    {new Date(
                                        user.created_at,
                                    ).toLocaleDateString("id-ID", {
                                        dateStyle: "medium",
                                    })}
                                </td>

                                <td>
                                    <select
                                        value={user.role}
                                        disabled={currentUser?.id === user.id}
                                        onChange={(event) =>
                                            updateRole(
                                                user,
                                                event.target.value as
                                                    | "user"
                                                    | "admin",
                                            )
                                        }
                                        className="nb-input min-w-32"
                                    >
                                        <option value="user">User</option>

                                        <option value="admin">Admin</option>
                                    </select>
                                </td>
                            </tr>
                        ))}

                        {users.data.length === 0 && (
                            <tr>
                                <td
                                    colSpan={7}
                                    className="text-center font-black"
                                >
                                    Pengguna tidak ditemukan.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>

            <Pagination links={users.links} />
        </AdminLayout>
    );
}
