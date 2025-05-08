export type User = {
  created_at: string;
  email: string;
  email_verified_at: string | null;
  id: number;
  name: string;
  provider_id: string | null;
  provider_name: string | null;
  updated_at: string | null;
};

export type Auth = {
  userId?: number | null;
  name: string | null;
  email?: string;
  emailVerifiedAt?: string | null;
  remember?: string;
};

const initialState: Auth = {
  userId: null,
  name: null,
  email: '',
  emailVerifiedAt: '',
  remember: '',
};

export default class AuthState {
  userId?: number | null;
  name: string | null;
  email?: string;
  emailVerifiedAt?: string | null;
  remember?: string;

  constructor() {
    this.userId = initialState.userId;
    this.name = initialState.name;
    this.email = initialState.email;
    this.emailVerifiedAt = initialState.emailVerifiedAt;
    this.remember = initialState.remember;
  }

  get isLogined(): boolean {
    return !!this.userId;
  }
}
