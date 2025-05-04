import MainService from '@/services/main';
import AuthState, { Auth } from '@/states/auth';

export default class AuthService {
  main: MainService;
  auth: AuthState;

  constructor(main: MainService) {
    this.main = main;
    this.auth = main.root.auth;
  }

  setAuth(auth: Auth) {
    this.auth.userId = auth.id;
    this.auth.name = auth.name;
    this.auth.email = auth.email;
    this.auth.email_verified_at = auth.email_verified_at;
    this.auth.isLogined = !!this.auth.userId;
    this.main.setRootState();
  }
}
