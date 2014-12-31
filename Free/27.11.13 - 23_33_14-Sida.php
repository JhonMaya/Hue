<?php exit() ?>--by Sida 92.28.112.34
--[[Fancy as fuck Toasty Event]]
local spriteshow
local distance = -300
local current = 0
local direction = "L"
local timer = 0
local Active = false
local ActiveEnds = 0
local NextToast = 0

function OnLoad()
	spriteshow = createSprite("/Toasty/toasty.dds")
end	

function OnTick()
	if (GetTickCount() > timer + 1500 or timer == 0) and direction == "p" then
		direction = "R"
	end
	DoToastie()
end

function OnHeroKilled(Killed, Killer)
	if Killer.isMe and GetTickCount() > NextToast then
		Active = true
		ActiveEnds = GetTickCount()
		NextToast = GetTickCount() + 7000
	end
end

function OnRecvPacket(p)
    if p.header == 0x5D then
        p.pos = 1 
        killed = objManager:GetObjectByNetworkId(p:DecodeF())
        killer = objManager:GetObjectByNetworkId(p:DecodeF())
        OnHeroKilled(killed, killer)
    end 
end

function OnDraw()
	if Active then
		spriteshow:Draw(WINDOW_W - 30 - current, WINDOW_H - 250, 0xFF)
		if current == 180 and not DoingTimer then
			direction = "p"
			timer = GetTickCount()
			DoingTimer = true
			Sound = true
		elseif current == 0 and GetTickCount() < ActiveEnds then
			direction = "L"
			Active = false
		end

		if direction == "L" then
			current = current + 1
		elseif direction == "R" then
			current = current - 1
			DoingTimer = false
		end 
	end
end

function DoToastie()
	if Sound then
		Sound = false
		PlaySoundPS(LIB_PATH.."../../Sprites/Toasty/toasty.wav")
	end
end

function PlaySoundPS(path)
    os.executePowerShellAsync('(New-Object Media.SoundPlayer "'..path..'").PlaySync();')
end