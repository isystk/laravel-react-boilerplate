import MainService from "@/services/main";

export interface Auth {
    auth: boolean;
    id?: number | null;
    name: string | null;
    email?: string;
    remember?: string;
    csrf?: string;
    request?: string;
    session?: string;
}

export type Session = {
    created_at: string;
    email: string;
    email_verified_at: string | null;
    id: number;
    name: string;
    provider_id: string | null;
    provider_name: string | null;
    updated_at: string | null;
};

export default class AuthService {
    main: MainService;
    isLogined: boolean;
    userId?: number | null;
    name: string | null;
    email?: string;
    email_verified_at?: string | null;
    remember?: string;
    csrf?: string;

    constructor(main: MainService) {
        this.main = main;
        this.isLogined = false;
        this.userId = null;
        this.name = null;
        this.email = "";
        this.email_verified_at = "";
        this.remember = "";
        this.csrf = "";
    }

    setSession(session: Session) {
        this.userId = session.id;
        this.name = session.name;
        this.email = session.email;
        this.email_verified_at = session.email_verified_at;
        this.isLogined = !!this.userId;
        this.main.setAppRoot();
    }

    setCSRF(csrf: string) {
        this.csrf = csrf;
        this.main.setAppRoot();
    }
}
