import MainService from '@/services/main';
import AuthState, { Session } from '@/state/auth';

export default class AuthService {
  main: MainService;
  auth: AuthState;

  constructor(main: MainService) {
    this.main = main;
    this.auth = main.root.auth;
  }

  setSession(session: Session) {
    this.auth.userId = session.id;
    this.auth.name = session.name;
    this.auth.email = session.email;
    this.auth.email_verified_at = session.email_verified_at;
    this.auth.isLogined = !!this.auth.userId;
    this.main.setRootState();
  }

  setCSRF(csrf: string) {
    this.auth.csrf = csrf;
    this.main.setRootState();
  }
}
