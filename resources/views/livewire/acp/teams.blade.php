<x-acp.acp-view x-data="{modalOpen: false}">
    <x-page-title>
        <span>Teams</span>
        <div class="ml-auto flex items-center">
            <a class="button text-sm cursor-pointer" @click="$wire.createModalOpen = true">
                <i class="fas fa-plus mr-2"></i> Add Team
            </a>
        </div>
    </x-page-title>

    <x-card class="p-0 py-5">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Team Name</th>
                    <th>Members</th>
                    <th>Last WoWAudit Sync</th>
                    <th class="!text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $team)
                    <tr>
                        <td>{{$team->name}}</td>
                        <td>{{$team->characters()->count()}}</td>
                        <td>{{$team->wowaudit_secret ? now()->diffForHumans() : 'Never'}}</td>
                        <td>
                            <div class="flex flex-wrap gap-x-2 justify-end">

                                <i class="fas fa-rotate hover:text-green-500 transition-colors cursor-pointer" wire:click="syncTeam('{{$team->public_id}}')"></i>
                                <i class="fas fa-pencil hover:text-indigo-500 transition-colors cursor-pointer"></i>
                                <i
                                    class="fas fa-trash hover:text-red-500 transition-colors cursor-pointer"
                                    wire:click="deleteTeam('{{$team->public_id}}')"
                                    wire:confirm="Delete team {{$team->name}}?"
                                ></i>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </x-card>

    <x-modal open-property="createModalOpen">
        <div clas="flex flex-col">
            <div class="flex flex-nowrap p-6">
                <span class="text-lg font-bold">Create Team</span>
                <div class="ml-auto pr-3">
                    <i class="fas fa-x hover:text-red-500 transition-colors cursor-pointer" @click="$wire.createModalOpen = false"></i>
                </div>
            </div>
            <form wire:submit="createTeam" class="grid grid-cols-2 px-6 gap-y-2">
                <x-form.input wire:model="createTeamForm.teamName" placeholder="Team Name" name="createTeamForm.teamName" container-class="col-span-2" />
                <x-form.hint for="createTeamForm.teamName" />
                <x-form.input wire:model="createTeamForm.wowauditSecret" placeholder="Wowaudit API Secret" name="createTeamForm.wowauditSecret" container-class="col-span-2" />
                <x-form.hint for="createTeamForm.wowauditSecret" />

                <x-form.button class="col-start-2 ml-auto mt-2 my-4">
                    <x-spinner wire:loading />
                    <span wire:loading>Creating ...</span>
                    <span wire:loading.remove>Create</span>
                </x-form.button>
            </form>
        </div>
    </x-modal>
</x-acp.acp-view>
