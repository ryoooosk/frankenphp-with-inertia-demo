import { useForm } from '@inertiajs/react';

type Scope = {
  id: string;
  description: string;
};

type AuthorizeProps = {
  client: {
    name: string;
  };
  scopes: Scope[];
  authToken: string;
};

export default function Authorize({ client, scopes, authToken }: AuthorizeProps) {
  const approveForm = useForm({ auth_token: authToken });
  const denyForm = useForm({ auth_token: authToken });

  const handleApprove = (e: React.FormEvent) => {
    e.preventDefault();
    approveForm.post('/oauth/authorize');
  };

  const handleDeny = (e: React.FormEvent) => {
    e.preventDefault();
    denyForm.delete('/oauth/authorize');
  };

  return (
    <div className="flex-1 flex items-center justify-center px-4 my-3">
      <div className="w-full max-w-sm">
        <h2 className="text-2xl font-bold text-slate-800 mb-2">認可リクエスト</h2>
        <p className="text-sm text-slate-600 mb-6">
          <span className="font-semibold text-slate-800">{client.name}</span>{' '}
          があなたのアカウントへのアクセスを求めています。
        </p>

        {scopes.length > 0 && (
          <div className="mb-6">
            <h3 className="text-sm font-medium text-slate-700 mb-2">
              要求されている権限:
            </h3>
            <ul className="space-y-1">
              {scopes.map((scope) => (
                <li
                  key={scope.id}
                  className="flex items-center text-sm text-slate-600"
                >
                  <span className="mr-2 text-slate-400">&#10003;</span>
                  {scope.description}
                </li>
              ))}
            </ul>
          </div>
        )}

        <div className="flex gap-3">
          <form onSubmit={handleApprove} className="flex-1">
            <button
              type="submit"
              disabled={approveForm.processing}
              className="w-full py-2 px-4 bg-slate-800 text-white font-medium rounded-md hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              許可する
            </button>
          </form>
          <form onSubmit={handleDeny} className="flex-1">
            <button
              type="submit"
              disabled={denyForm.processing}
              className="w-full py-2 px-4 bg-white text-slate-800 font-medium rounded-md border border-slate-300 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
              拒否する
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}
