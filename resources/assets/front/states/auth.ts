export type User = {
  name: string;
  email: string;
  email_verified_at: string | null;
  avatar_url: string | null;
};

const initialState: User = {
  name: '',
  email: '',
  email_verified_at: null,
  avatar_url: null,
};

export default class AuthState {
  name: string | null;
  email: string | null;
  email_verified_at: string | null;
  avatar_url: string | null;

  constructor() {
    this.name = initialState.name;
    this.email = initialState.email;
    this.email_verified_at = initialState.email_verified_at;
    this.avatar_url = initialState.avatar_url;
  }

  get isLogined(): boolean {
    return !!this.email;
  }
}
