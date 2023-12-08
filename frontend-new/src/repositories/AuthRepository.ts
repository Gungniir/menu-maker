import Repository from '../plugins/axios';
import type {AxiosPromise} from "axios";

const base = 'auth';

export default {
  login(email: string, password: string): AxiosPromise<{
    access_token: string,
    token_type: string,
    expires_in: number
  }> {
    return Repository.post(base + '/login', {
      email,
      password
    });
  }
}
