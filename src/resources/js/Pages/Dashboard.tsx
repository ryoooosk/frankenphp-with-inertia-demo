import { usePage } from '@inertiajs/react';
import type { AuthProps } from '../types';

export default function Dashboard() {
  const { auth } = usePage<AuthProps>().props;

  return (
    <div className="flex-1 flex items-center justify-center px-4">
      <div className="w-full max-w-md text-center">
        <h2 className="text-2xl font-bold text-slate-800 mb-2">
          ようこそ、{auth.user?.name}さん
        </h2>
        <p className="text-slate-600">{auth.user?.email}</p>
      </div>
    </div>
  );
}
