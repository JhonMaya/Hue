<?php exit() ?>--by klokje 80.57.15.158
_G.BUFF_NONE = 0
_G.BUFF_GLOBAL = 1
_G.BUFF_BASIC = 2 
_G.BUFF_DEBUFF = 3
_G.BUFF_STUN = 5
_G.BUFF_STEALTH = 6
_G.BUFF_SILENCE = 7
_G.BUFF_TAUNT = 8
_G.BUFF_SLOW = 10
_G.BUFF_ROOT = 11
_G.BUFF_DOT = 12
_G.BUFF_REGENERATION = 13
_G.BUFF_SPEED = 14
_G.BUFF_MAGIC_IMMUNE = 15
_G.BUFF_PHYSICAL_IMMUNE = 16 
_G.BUFF_IMMUNE = 17
_G.BUFF_Vision_Reduce = 19
_G.BUFF_FEAR = 21
_G.BUFF_CHARM = 22
_G.BUFF_POISON = 23
_G.BUFF_SUPPRESS = 24
_G.BUFF_BLIND = 25
_G.BUFF_STATS_INCREASE = 26 
_G.BUFF_STATS_DECREASE = 27 
_G.BUFF_FLEE = 28
_G.BUFF_KNOCKUP = 29
_G.BUFF_KNOCKBACK = 30
_G.BUFF_DISARM = 31

_ENV = AdvancedCallback:register('OnLoseBuff', 'OnGainBuff', 'OnUpdateBuff')

function OnRecvPacket(p)


	if p.header == 0xB6 then 
		p.pos = 1
		local networkID = p:DecodeF()
        local buffSlot = p:Decode1()
        local bufftype = p:Decode1()
        local stackCount = p:Decode1()
        local visible = p:Decode1()
        local buffID = p:Decode4()
        local targetID = p:Decode4()
        local unknown = p:Decode4() 
        local time = p:DecodeF()
        local sourceNetworkId = p:DecodeF()
        DelayAction1(function(buffID, bufftype, buffSlot, targetNetworkId, sourceNetworkId, stackCount, time, visible)
            local targetbuff = objManager:GetObjectByNetworkId(networkID)
            if targetbuff == nil then return end 
            local tempbuff = targetbuff:getBuff(buffSlot+1)
            local name = tempbuff and tempbuff.name or ""
            AdvancedCallback:OnGainBuff(targetbuff, {name = name, stack = stackCount, slot=buffSlot+1, duration = time, startT = GetGameTimer(), visible = visible,  endT=GetGameTimer()+time, source = objManager:GetObjectByNetworkId(sourceNetworkId), type = bufftype})
        end, 0, {buffID, bufftype, buffSlot, targetNetworkId, sourceNetworkId, stackCount, time, visible})
    elseif p.header == 0x1C then 
        p.pos = 1
        local networkID = p:DecodeF()
        local buffSlot = p:Decode1()
        local stackCount = p:Decode1()
        local time = p:DecodeF()
        local timeBuffAlreadyOnTarget = p:DecodeF()
        local sourceNetworkId = p:DecodeF()
        local targetbuff = objManager:GetObjectByNetworkId(networkID)
        if targetbuff == nil then return end 
        DelayAction1(function(targetbuff, stackCount, buffSlot,time, sourceNetworkId) 
             local tempbuff = targetbuff:getBuff(buffSlot+1)
            local name = tempbuff and tempbuff.name or ""
            AdvancedCallback:OnUpdateBuff(targetbuff, {name = name, type = BUFF_NONE, stack = stackCount, slot=buffSlot+1, duration = time, startT = GetGameTimer(), endT=GetGameTimer()+time, source = objManager:GetObjectByNetworkId(sourceNetworkId)})
        end, 0, {targetbuff, stackCount, buffSlot,time, sourceNetworkId})
    elseif p.header == 0x2F then
        p.pos = 1
        local networkID = p:DecodeF()
        local buffSlot = p:Decode1()
        local timeBuffAlreadyOnTarget = p:DecodeF()
        local time = p:DecodeF()
        local sourceNetworkId = p:DecodeF()
        local targetbuff = objManager:GetObjectByNetworkId(networkID)
        if targetbuff == nil then return end 
        DelayAction1(function(targetbuff, buffSlot,time, sourceNetworkId) 
            local tempbuff = targetbuff:getBuff(buffSlot+1)
            local name = tempbuff and tempbuff.name or ""
            AdvancedCallback:OnUpdateBuff(targetbuff, {name = name, type = BUFF_NONE, stack = 1, slot=buffSlot+1, duration = time, startT = GetGameTimer(), endT=GetGameTimer()+time, source = objManager:GetObjectByNetworkId(sourceNetworkId)})
    end, 0, {targetbuff, buffSlot,time, sourceNetworkId})
    elseif p.header == 0x7A then 
        p.pos = 1
        local networkID = p:DecodeF()
        local buffSlot = p:Decode1()
        local buffID = p:Decode4()
        local time = p:DecodeF()
        local targetbuff = objManager:GetObjectByNetworkId(networkID)
        if targetbuff == nil then return end 
        DelayAction1(function(targetbuff, stackCount, buffSlot) 
            local tempbuff = targetbuff:getBuff(buffSlot+1)
            local name = tempbuff and tempbuff.name or ""
            AdvancedCallback:OnLoseBuff(targetbuff, {name = name, type = BUFF_NONE, stack = stackCount, slot=buffSlot+1, duration = 0, startT = 0, endT = 0})
        end, 0, {targetbuff, stackCount, buffSlot})
    end
end

local delayedActions, delayedActionsExecuter = {}, nil 
function DelayAction1(func, delay, args) --delay in seconds
    if not delayedActionsExecuter then
        function delayedActionsExecuter()
            for t, funcs in ipairs(delayedActions) do
                if funcs.time <= os.clock() then
                    funcs.func(table.unpack(funcs.args or {}))
                    --delayedActions[t] = nil
                    table.remove(delayedActions, t)
                end
            end
        end

        AddTickCallback(delayedActionsExecuter)
    end
    local t = os.clock() + (delay or 0)
    table.insert(delayedActions, {time = t, func = func, args = args })
end
