<?php exit() ?>--by ikita 183.178.170.22
function OnLoad()

newPath = SCRIPT_PATH .. "announcer_dlc_glados\\"
newPath = string.gsub(newPath, "\\", "/" )
newPath = string.gsub(newPath, "/", "\\\\" )

end

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

function OnTick()

end

function OnCreateObj(object)
if object.name == "Respawn_glb.troy" then
	if GetDistance(object) < 50 then
		PlaySoundPS(newPath .. respawnTable[math.random(#respawnTable)], 1000)
	end
end
end