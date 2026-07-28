export type NavigationItem = {
    label: string;
    routeName: string;
    pattern?: string;
};

export const publicNavigation: NavigationItem[] = [
    {
        label: "Beranda",
        routeName: "home",
    },
    {
        label: "Tantangan",
        routeName: "challenges.index",
    },
    {
        label: "Peringkat",
        routeName: "leaderboard",
    },
    {
        label: "Tentang",
        routeName: "about",
    },
];

export const authenticatedNavigation: NavigationItem[] = [
    {
        label: "Ringkasan",
        routeName: "dashboard",
    },
    {
        label: "Tantangan",
        routeName: "challenges.index",
    },
    {
        label: "Riwayat",
        routeName: "history.index",
    },
    {
        label: "Peringkat",
        routeName: "leaderboard",
    },
];

export const adminNavigation: NavigationItem[] = [
    {
        label: "Ringkasan",
        routeName: "admin.dashboard",
        pattern: "admin.dashboard",
    },
    {
        label: "Statistik",
        routeName: "admin.statistics.index",
        pattern: "admin.statistics.*",
    },
    {
        label: "Tantangan",
        routeName: "admin.challenges.index",
        pattern: "admin.challenges.*",
    },
    {
        label: "Kategori",
        routeName: "admin.categories.index",
        pattern: "admin.categories.*",
    },
    {
        label: "Kesulitan",
        routeName: "admin.difficulties.index",
        pattern: "admin.difficulties.*",
    },
    {
        label: "Pengguna",
        routeName: "admin.users.index",
        pattern: "admin.users.*",
    },
    {
        label: "Jawaban",
        routeName: "admin.submissions.index",
        pattern: "admin.submissions.*",
    },
];
