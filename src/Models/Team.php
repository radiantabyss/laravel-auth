<?php
namespace Lumi\Auth\Models;

use Lumi\Auth\Services\ClassName;

class Team extends Model
{
    protected $table = 'team';

    public function user() {
        return $this->belongsTo(ClassName::Model('User'));
    }

    public function loadMeta($keys = []) {
        $metas = ClassName::Model('TeamMeta')::where('team_id', $this->id)->get();
        $item_meta = [];

        foreach ( $metas as $meta ) {
            if ( !count($keys) ) {
                $item_meta[$meta->key] = $meta->value;
            }
            else if ( in_array($meta->key, $keys) ) {
                $item_meta[$meta->key] = $meta->value;
            }
        }

        $this->meta = $item_meta;
    }

    public static function loadMetaForMany($items, $keys = []) {
        if ( !count($items) ) {
            return $items;
        }

        $grouped_metas = ClassName::Model('TeamMeta')::whereIn('team_id', pluck($items))->get()->groupBy('team_id');

        foreach ( $items as $item ) {
            $metas = $grouped_metas[$item->id] ?? [];
            $item_meta = [];

            foreach ( $metas as $meta ) {
                if ( !count($keys) ) {
                    $item_meta[$meta->key] = $meta->value;
                }
                else if ( in_array($meta->key, $keys) ) {
                    $item_meta[$meta->key] = $meta->value;
                }
            }

            $item->meta = $item_meta;
        }

        return $items;
    }
}
