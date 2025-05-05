export type Auth = {
  created_at: string;
  email: string;
  email_verified_at: string | null;
  id: number;
  name: string;
  provider_id: string | null;
  provider_name: string | null;
  updated_at: string | null;
};

export default class AuthState {
  userId?: number | null;
  name: string | null;
  email?: string;
  email_verified_at?: string | null;
  remember?: string;

  constructor() {
    this.userId = null;
    this.name = null;
    this.email = '';
    this.email_verified_at = '';
    this.remember = '';
  }

  get isLogined(): boolean {
    return this.userId !== null;
  }
}
