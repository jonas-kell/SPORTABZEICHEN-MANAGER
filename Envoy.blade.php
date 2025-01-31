@setup
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $app_dirs = ['~/deploy_sportabzeichen'];
@endsetup

@servers(['live' => env('DEPLOY_USER') . '@' . env('DEPLOY_HOST')])

@task('deploy', ['on' => 'live'])
    @foreach ($app_dirs as $app_dir)
        @php
            $repository = 'sportabzeichen:jonas-kell/SPORTABZEICHEN-MANAGER.git';
            $composer = '~/composer/composer.phar';
            $releases_dir = $app_dir . '/releases';
            $config_storage = $app_dir . '/config_storage';
            $file_storage = $app_dir . '/storage';
            $release = date('YmdHis');
            $new_release_dir = $releases_dir . '/' . $release;
            $php = '/usr/bin/php8.4-cli';
        @endphp

        {{-- Clone Repository --}}
        echo 'Cloning repository'
        cd {{ $releases_dir }}
        git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
        cd {{ $new_release_dir }}
        git reset --hard {{ $commit }}
        {{-- Composer --}}
        echo "Starting deployment ({{ $release }})"
        cd {{ $new_release_dir }}
        {{ $php }} {{ $composer }} install --prefer-dist --no-scripts -o
        {{-- Copying permanent Files --}}
        echo "Copy .env file"
        cp {{ $config_storage }}/.env {{ $new_release_dir }}/.env
        echo "Copy .htaccess file"
        rm {{ $new_release_dir }}/public/.htaccess
        cp {{ $config_storage }}/.htaccess {{ $new_release_dir }}/public/.htaccess
        echo "Linking Permanent Storage"
        rm -rf {{ $new_release_dir }}/storage
        ln -nfs {{ $file_storage }} {{ $new_release_dir }}/storage
        {{-- Clear Config --}}
        echo "Clearing Configuration"
        cd {{ $new_release_dir }}
        {{ $php }} artisan cache:clear
        {{ $php }} artisan config:cache
        {{ $php }} artisan route:cache
        {{ $php }} artisan view:cache
        {{-- Migrate Database --}}
        echo "Migrating Database"
        {{ $php }} artisan migrate --force
        {{-- Link current release --}}
        echo 'Linking current release'
        ln -nfs {{ $releases_dir }}/{{ $release }}/public {{ $app_dir }}/current
        echo 'Directory {{ $releases_dir }}/{{ $release }}/public has been linked'

        {{-- Commands to execute --}}
        echo 'Start Locally: '
        echo 'docker exec -it sportabzeichen-manager-quasar-1 npm install'
        echo 'docker exec -it sportabzeichen-manager-quasar-1 quasar build'
        echo 'scp -r -o PubkeyAuthentication=no -o PreferredAuthentications=password frontend/dist {{ env('DEPLOY_USER') }}@{{ env('DEPLOY_HOST') }}:{{ $new_release_dir }}/frontend/'
        echo 'ssh -o PubkeyAuthentication=no -o PreferredAuthentications=password {{ env('DEPLOY_USER') }}@{{ env('DEPLOY_HOST') }}'
        echo 'cd {{ $new_release_dir }}'
        echo '{{ $php }} artisan storage:link'
    @endforeach
@endtask
