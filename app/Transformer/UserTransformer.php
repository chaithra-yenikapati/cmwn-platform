<?php

namespace app\Transformer;

use app\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    // protected $defaultIncludes = [
    //     'friends'
    // ];

    protected $availableIncludes = [
        'friends',
        'groups',
        'children',
        'guardians',
        'blockedfriends',
        'pendingfriends',
        'friendrequests',
        'organizations',
        'districts',
        'games',
        'flips',
        'images',
        'roles',
    ];

    /**
     * Turn this item object into a generic array.
     *
     * @return array
     */
    public function transform(User $user)
    {
        $data = [
            'uuid' => $user->uuid,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'gender' => $user->gender,
            'birthdate' => $user->birthdate,
            'joined' => (string) $user->created_at,
            'relationship' => $user->relationship,
        ];

        return $data;
    }

    /**
     * Embed Friends.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeRoles(User $user)
    {
        $roles = $user->getRoles();

        return $this->collection($roles, new RoleTransformer());
    }

    /**
     * Embed Friends.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeFriends(User $user)
    {
        $friends = $user->friends;

        return $this->collection($friends, new self());
    }

    /**
     * Embed friendrequests.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeFriendRequests(User $user)
    {
        $friendrequests = $user->friendrequests;

        return $this->collection($friendrequests, new self());
    }

    /**
     * Embed blockedfriends.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeBlockedFriends(User $user)
    {
        $blockedfriends = $user->blockedfriends;

        return $this->collection($blockedfriends, new self());
    }

    /**
     * Embed pendingfriends.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includePendingFriends(User $user)
    {
        $pendingfriends = $user->pendingfriends;

        return $this->collection($pendingfriends, new self());
    }
    /**
     * Embed Groups.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeGroups(User $user)
    {
        $groups = $user->groups;

        return $this->collection($groups, new GroupTransformer());
    }

    /**
     * Embed Guardian.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeGuardians(User $user)
    {
        $guardians = $user->guardians;

        return $this->collection($guardians, new self());
    }

    /**
     * Embed Children.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeChildren(User $user)
    {
        $children = $user->children;

        return $this->collection($children, new self());
    }

    /**
     * Embed Districts.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeDistricts(User $user)
    {
        $districts = $user->districts;

        return $this->collection($districts, new DistrictTransformer());
    }

    /**
     * Embed Organizations.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeOrganizations(User $user)
    {
        $organizations = $user->organizations;

        return $this->collection($organizations, new OrganizationTransformer());
    }

    /**
     * Embed Image.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeImages(User $user)
    {
        $image = $user->images;

        return $this->collection($image, new ImageTransformer());
    }

    /**
     * Embed Game.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeGames(User $user)
    {
        $games = $user->games;

        return $this->collection($games, new GameTransformer());
    }

    /**
     * Embed Flip.
     *
     * @return League\Fractal\Resource\Collection
     */
    public function includeFlips(User $user)
    {
        $flips = $user->flips;

        return $this->collection($flips, new FlipTransformer());
    }
}
