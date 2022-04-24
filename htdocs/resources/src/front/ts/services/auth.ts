import MainService from "@/services/main";

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
    auth: boolean;
    id?: number | null;
    name: string | null;
    email?: string;
    remember?: string;
    csrf?: string;
    request?: string;
    session?: Session;

    constructor(main: MainService) {
        this.main = main;
        this.auth = false;
        this.id = null;
        this.name = null;
        this.email = "";
        this.remember = "";
        this.csrf = "";
        this.request = "";
    }

    setSession(session: Session) {
        this.session = session;
    }

    setCSRF(csrf: string) {
        this.csrf = csrf;
    }
}
