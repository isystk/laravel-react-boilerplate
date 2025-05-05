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
  email_verified_at?: string | null;
  remember?: string;
};

const initialState: Auth = {
  userId: null,
  name: null,
  email: '',
  email_verified_at: '',
  remember: '',
};

export default class AuthState {
  userId?: number | null;
  name: string | null;
  email?: string;
  email_verified_at?: string | null;
  remember?: string;

  constructor() {
    this.userId = initialState.userId;
    this.name = initialState.name;
    this.email = initialState.email;
    this.email_verified_at = initialState.email_verified_at;
    this.remember = initialState.remember;
  }

  get isLogined(): boolean {
    return this.userId !== null;
  }
}
