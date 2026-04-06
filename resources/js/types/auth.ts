export type User = {
    id: number;
    name: string;
    known_as?: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    shifts: Shift[];
    open_shift: Shift | false;
    [key: string]: unknown;
};

export type Shift = {
    id: number;
    user_id: number;
    start_time: string;
    end_time: string;
    status: string;
    user?: User;
}

export type Auth = {
    user: User;
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
