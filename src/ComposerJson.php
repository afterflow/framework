<?php

namespace Afterflow\Framework;


use Afterflow\Framework\Concerns\WorksWithJsonFiles;

/**
 * Class ComposerJson
 * @package Afterflow\Framework
 */
class ComposerJson {
    use WorksWithJsonFiles;

    /**
     * @var string
     */
    protected $filename = 'composer.json';

    public function hasPackages( $packages ) {
        $c = $this->read();

        $require = collect( $c[ 'require' ] )->merge( $c[ 'require-dev' ] )->keys();
        foreach ( $packages as $package ) {
            if ( ! $require->contains( $package ) ) {
                return false;
            }
        }

        return true;
    }

    public function addRequire( $vendorName, $version = '@dev' ) {
        $c = $this->read();


        $repos = $c[ 'require' ] ?? [];
        foreach ( $repos as $name => $r ) {
            if ( $name == $vendorName ) {
                return $c;
            }
        }

        $repos[ $vendorName ] = '@dev';

        $composerJson[ 'require' ] = $repos;

        $this->write( $c );

        return;
    }

    public function addPathRepository( $path ) {

        $c = $this->read();


        $repos = $c[ 'repositories' ] ?? [];
        foreach ( $repos as $r ) {
            if ( $r[ 'url' ] == $path ) {
                return;
            }
        }

        $repos[] = [
            'type' => 'path',
            'url'  => $path,
        ];

        $c[ 'repositories' ] = array_values( $repos );

        $this->write( $c );

        return;

    }

    /**
     * @param $files
     */
    public function addAutoloadFiles( $files ) {
        $files = is_array( $files ) ? $files : [ $files ];
        $c     = $this->read();

        $c[ 'autoload' ]            = isset( $c[ 'autoload' ] ) ? $c[ 'autoload' ] : [];
        $c[ 'autoload' ][ 'files' ] = isset( $c[ 'autoload' ][ 'files' ] ) ? $c[ 'autoload' ][ 'files' ] : [];
        $c[ 'autoload' ][ 'files' ] = collect( $c[ 'autoload' ][ 'files' ] )->merge( $files )->unique()->toArray();

        $this->write( $c );
    }

}
