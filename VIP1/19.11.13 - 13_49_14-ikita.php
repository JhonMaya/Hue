<?php exit() ?>--by ikita 183.178.170.22
function OnLoad()

newPath = SCRIPT_PATH .. "announcer_dlc_glados\\"
newPath = string.gsub(newPath, "\\", "/" )
newPath = string.gsub(newPath, "/", "\\\\" )
secTime = nil
sound30played = false
sound10played = false
sound5played = false
sound0played = false
soundflplayed = false

--loops from 1 to max
respawnTable = {
"ann_glados_followup_respaw_01.wav", 
"ann_glados_followup_respaw_02.wav", 
"ann_glados_followup_respaw_03.wav", 
"ann_glados_followup_respaw_04.wav", 
"ann_glados_followup_respaw_05.wav", 
"ann_glados_followup_respaw_06.wav", 
"ann_glados_followup_respaw_07.wav", 
"ann_glados_followup_respaw_08.wav", 
"ann_glados_followup_respaw_09.wav", 
"ann_glados_followup_respaw_13.wav", 
"ann_glados_followup_respaw_14.wav", 
"ann_glados_followup_respaw_16.wav", 
"ann_glados_followup_respaw_17.wav"
}

thirtySecondTable = {
"ann_glados_count_battle_30_01.wav",
"ann_glados_count_battle_30_02.wav"
}

tenSecondTable = {
"ann_glados_count_battle_10_01.wav",
"ann_glados_count_battle_10_02.wav"
}

battleBeginsTable = {
"ann_glados_battle_begin_01.wav",
"ann_glados_battle_begin_02.wav",
"ann_glados_battle_begin_03.wav",
"ann_glados_battle_begin_04.wav"
}

end




function OnTick()

--print(GetTickCount()/1000)
	--30 seconds to battle
	if secTime ~= nil and GetTickCount()/1000 - secTime > 57 and GetTickCount()/1000 - secTime < 63 and sound30played == false then
		PlaySoundPS(newPath .. thirtySecondTable[math.random(#thirtySecondTable)], 1000)
		sound30played = true
	end
	
	--10 seconds to battle
	if secTime ~= nil and GetTickCount()/1000 - secTime > 77 and GetTickCount()/1000 - secTime < 83 and sound10played == false then
		PlaySoundPS(newPath .. tenSecondTable[math.random(#tenSecondTable)], 500)
		sound10played = true
	end
	
	-- battle 5 seconds
	if secTime ~= nil and GetTickCount()/1000 - secTime > 82 and GetTickCount()/1000 - secTime < 88 and sound5played == false then
		PlaySoundPS(newPath .. "ann_glados_count_battle_01.wav", 500)
		sound5played = true
	end
	
	--begin battle
	if secTime ~= nil and GetTickCount()/1000 - secTime > 87 and GetTickCount()/1000 - secTime < 93 and sound0played == false then
		PlaySoundPS(newPath .. battleBeginsTable[math.random(#battleBeginsTable)], 1000)
		sound0played = true
	end
	
	
	--begin battle follow
	if secTime ~= nil and GetTickCount()/1000 - secTime > 100 and GetTickCount()/1000 - secTime < 106 and soundflplayed == false then
		PlaySoundPS(newPath .. "ann_glados_battle_begin_follow.wav", 1000)
		soundflplayed = true
	end
	
end

function OnCreateObj(object)
--print(object.name)
	if object.name == "SpawnBeacon.troy" then
		secTime = GetTickCount()/1000
	end
	if object.name == "Respawn_glb.troy" then
		if GetDistance(object) < 50 then
			PlaySoundPS(newPath .. respawnTable[math.random(#respawnTable)], 1000)
		end
	end
end